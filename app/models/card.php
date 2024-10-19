<?php
/**
 * 
 * Represents a Card
 * 
 * @author NekoCari
 *
 */

class Card extends DbRecordModel {
    
    protected $id, $name, $deck, $number, $owner, $status_id, $date, $utc, $owner_obj, $deck_obj, $status;
    
    private $is_tradeable, $counter, $possetion_counter;
    
    protected static 
        $db_table = 'cards',
        $db_pk = 'id',
        $db_fields = array('id','deck','number','name','owner','status_id','date', 'utc'),
        $sql_order_by_allowed_values = array('id','name');
    
    private static $tpl_html, $accepted_stati, $accepted_stati_obj;
    
    public function __construct() {
        parent::__construct();
        if(is_null(self::$tpl_html)){
            self::$tpl_html = file_get_contents(PATH.'app/views/multilang/templates/card_image_temp.php');
        }
    }
    
    public static function getAcceptedStati(){
    	if(is_null(self::$accepted_stati)){
    		foreach(self::getAcceptedStatiObj() as $status){
    			self::$accepted_stati[$status->getId()] = $status->getName();
    		}
    	}
    	return self::$accepted_stati;
    }
    
    /**
     * 
     * @return CardStatus[]
     */
    public static function getAcceptedStatiObj(){
    	if(is_null(self::$accepted_stati_obj)){
    		$stati = CardStatus::getAll(['position'=>'ASC']);
    		foreach($stati as $status){
    			self::$accepted_stati_obj[$status->getId()] = $status;
    		}
    	}
    	return self::$accepted_stati_obj;
    }
    
    /**
     * 
     * @param int $id
     * @return Card|NULL
     */
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    public static function getDuplicatesByMemberId($member_id, $tradeable = true, $status_id = null, $order_settings = ['name'=>'ASC']){
        $db = Db::getInstance();
        $duplicates = array();
        if($tradeable){
        	$tradable_stati = CardStatus::getWhere(['tradeable'=>1]);
        	$sql_where['query_part']  = "WHERE owner = $member_id AND status_id IN(";
        	foreach($tradable_stati as $status){
        		$sql_where['query_part'] .= $status->getId().',';
        	}
        	$sql_where['query_part'] = substr($sql_where['query_part'] ,0,-1).') ';
        	$sql_where['param_array'] = null;
        }elseif(is_int($status_id)){
        	$sql_where = self::buildSqlPart('where',['owner'=>$member_id,'status_id'=>$status_id]);
        }else{
        	throw new ErrorException('has to be set to all tradeable stati or you need to define a single status id');
        }
        if(key_exists('name',$order_settings)){
        	$order_settings['deck.deckname'] = $order_settings['name'];
        	$order_settings['card.number'] = $order_settings['name'];
			unset($order_settings['name']);
        }
        $sql_order = self::buildSqlPart('order_by',$order_settings);
        $sql = "SELECT  cards.*, MIN(cards.id) as id, COUNT(cards.id) as counter FROM cards  ".
          		"LEFT JOIN decks ON decks.id = cards.deck ".$sql_where['query_part'].
        		" GROUP BY name HAVING counter > 1 ".$sql_order['query_part'];
        
        $req = $db->prepare($sql);
        $req->execute($sql_where['param_array']);
        foreach ($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $card){
        	$duplicates[] = ['card'=>$card,'possessionCounter'=>$card->counter];
        }
        return $duplicates;
    }
    
    /**
     * @deprecated use object oriented approach
     * @param int $id
     * @param string $status
     * @param int $user member id
     * @return boolean
     */
    public static function changeStatusById($id,$status,$user) {
        $db = DB::getInstance();
        if($status == 'collect'){
            $card_name = self::getById($id)->getName();
            $req = $db->prepare('SELECT * FROM cards WHERE owner = :user AND status = :status AND name = :name');
            $req->execute(array(':status'=>$status,':name'=>$card_name,':user'=>$user));
            if($req->rowCount() > 0){
                return false;
            }
        }
        $req = $db->prepare('UPDATE cards SET status = :status WHERE id = :id and owner = :user');
        return $req->execute(array(':status'=>$status,':id'=>$id,':user'=>$user));
    }
    
    
    /**
     *
     * @param int $member_id
     * @param int $status_id
     * @param boolean $only_tradeable
     * @return Card[]
     */
    public static function getMemberCardsByStatus($member_id, $status_id, $only_tradeable = false) {
    	$query = "SELECT c.* FROM ".self::getDbTableName()." c LEFT JOIN ".Carddeck::getDbTableName()." d ON c.deck = d.id
				WHERE owner = :owner AND status_id = :status_id ORDER BY d.deckname ASC, c.number ASC";
    	$req = Db::getInstance()->prepare($query);
    	$req->execute([':owner'=>$member_id,':status_id'=>$status_id]);
    	$cards = array();
    	foreach($req->fetchAll(PDO::FETCH_CLASS,get_called_class()) as $card){
    		if(!$only_tradeable OR ($only_tradeable AND $card->isTradeable())){
    			if(!key_exists($card->getName(), $cards)){
    				$cards[$card->getName()] = $card;
    			}else{
    				$cards[$card->getName()]->possession_counter++;
    			}
    		}
    	}
    	return $cards;
    }
    
    
    /**
     * 
     * @param int $member_id
     * @param int $status_id
     * @param int $compare_member_id
     * @param boolean $only_tradeable
     * @param boolean $include_hidden
     * @return Card[]
     */
    public static function getNeededMemberCardsByStatus($member_id, $status_id, $compare_member_id, $only_tradeable = true, $include_hidden = false) {
    	$not_tradeable_status_arr = CardStatus::getAll();
    	$not_tradeable_status_id_str = '';
    	foreach($not_tradeable_status_arr as $status){
    		if(!$status->isNew() AND ($status->isCollections() OR !$status->isTradeable()) AND ($include_hidden OR !$include_hidden AND $status->isPublic()) ){
    			$not_tradeable_status_id_str.= $status->getId().',';
    		}
    	}
    	$not_tradeable_status_id_str = substr($not_tradeable_status_id_str,0,-1);
    	
    	$query = "SELECT 
    				c.*, IF(compare_wish.id, 1, 0) as wishlist_flag, IF(need_decks.deck, 1, 0) as in_not_tradeable_flag
				FROM ".self::getDbTableName()." c 
				LEFT JOIN ".Carddeck::getDbTableName()." d ON c.deck = d.id
				LEFT JOIN (
					SELECT DISTINCT deck FROM cards WHERE owner = :compare_id AND status_id IN ( $not_tradeable_status_id_str) 
				) as need_decks ON need_decks.deck = c.deck
				LEFT JOIN ".MemberWishlistEntry::getDbTableName()." as compare_wish ON compare_wish.member_id = :compare_id AND compare_wish.deck_id = c.deck
				LEFT JOIN (
					SELECT DISTINCT deck, number FROM ".self::getDbTableName()." WHERE owner = :compare_id
				) as compare_cards ON (compare_cards.deck = need_decks.deck OR compare_cards.deck = compare_wish.deck_id) AND compare_cards.number = c.number
				WHERE c.owner = :owner AND c.status_id = :status_id  AND compare_cards.number IS NULL
				HAVING wishlist_flag = 1 OR in_not_tradeable_flag = 1 
				ORDER BY d.deckname ASC, c.number ASC";
    	// echo $query;
    	$req = Db::getInstance()->prepare($query);
    	$req->execute([':owner'=>$member_id,':compare_id'=>$compare_member_id,':status_id'=>$status_id]);
    	$cards = array();
    	foreach($req->fetchAll(PDO::FETCH_CLASS,get_called_class()) as $card){
    		if(!$only_tradeable OR ($only_tradeable AND $card->isTradeable())){
    			if(!key_exists($card->getName(), $cards)){
    				$cards[$card->getName()] = $card;
    			}else{
    				$cards[$card->getName()]->possession_counter++;
    			}
    		}
    	}
    	return $cards;
    }
    
    /**
     * 
     * @param int $member_id
     * @return Card[]
     */
    public static function getMemberCardsTradeable($member_id, $include_hidden = true) {
    	$tradeable_status_arr = CardStatus::getTradeable();
    	$tradeable_status_id_str = '';
    	foreach($tradeable_status_arr as $status){
    		if($include_hidden OR !$include_hidden AND $status->isPublic()){
    			$tradeable_status_id_str.= $status->getId().',';
    		}
    	}
    	$tradeable_status_id_str = substr($tradeable_status_id_str,0,-1);
    	
    	$query = "SELECT c.* FROM ".self::getDbTableName()." c 
				LEFT JOIN ".Carddeck::getDbTableName()." d ON c.deck = d.id
				LEFT JOIN ".Trade::getDbTableName()." t ON t.status = 'new' AND (offered_card = c.id OR requested_card = c.id)
				WHERE owner = :owner AND status_id IN( $tradeable_status_id_str ) AND t.id IS NULL 
				ORDER BY d.deckname ASC, c.number ASC";
    	$req = Db::getInstance()->prepare($query);
    	$req->execute([':owner'=>$member_id]);
    	$cards = array();
    	foreach($req->fetchAll(PDO::FETCH_CLASS,get_called_class()) as $card){
    		if(!key_exists($card->getName(), $cards)){
    			$cards[$card->getName()] = $card;
    		}else{
    			$cards[$card->getName()]->possession_counter++;
    		}
    	}
    	return $cards;
    }
    
    
    /**
     * @deprecated use update() instead
     */
    public function store() {
        return parent::update();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getDeckId() {
        return $this->deck;
    }
    
    public function getDeck() {
        if(!$this->deck_obj instanceof Carddeck){
            $this->deck_obj = Carddeck::getById($this->deck);
        }
        return $this->deck_obj;
    }
    
    public function getNumber() {
        return $this->number;
    }
    
    public function getStatus() {
    	if(!$this->status instanceof CardStatus){
    		$this->status = self::getAcceptedStatiObj()[$this->getStatusId()];
    	}
    	return $this->status;
    }
    public function getStatusName() {
    	return self::getAcceptedStati()[$this->getStatusId()];
    }
    public function getStatusId() {
    	return $this->status_id;
    }
    
    public function getOwner() {
        if(!$this->owner_obj instanceof Member){
            $this->owner_obj = Member::getById($this->owner);
        }
        return $this->owner_obj;
    }
    
    public function getOwnerId(){
        return $this->owner;
    }
        
    public function setStatusId($status_id) {
        if(array_key_exists($status_id, self::getAcceptedStati())){
            $this->status_id = $status_id;
            return true;
        }else{
            return false;
        }
    }
    
    public function setOwner($member) {
    	if($member instanceof Member){
    		$this->owner = $member->getId();
    		$this->owner_obj = $member;
    	}else{
	        $this->owner = $member;
	        $this->owner_obj = Member::getById($member);
    	}
        return true;
    }
    
    public function getDeckname() {
        return $this->getDeck()->getDeckname();
    }
    
    public function getImageUrl() {
        $setting_file_type = Setting::getByName('cards_file_type');
        $deckname = $this->getDeckname();
        $url = Carddeck::getDecksFolder().$deckname.'/'.$deckname.$this->getNumber().'.'.$setting_file_type->getValue();
        return $url;
    }
    
    public function getImageHtml() {
        $url = $this->getImageUrl();
        
        $tpl_placeholder = array('[WIDTH]','[HEIGHT]','[URL]');
        $replace = array($this->getDeck()->getType()->getCardWidth(), $this->getDeck()->getType()->getCardHeight(), $url);
        
        return str_replace($tpl_placeholder, $replace, self::$tpl_html);
    }
        
    public static function getFillerHtml($file_path,$img_width,$img_height){
    	if(is_null(self::$tpl_html)){
    		self::$tpl_html = file_get_contents(PATH.'app/views/multilang/templates/card_image_temp.php');
    	}
    	$tpl_placeholder = array('[WIDTH]','[HEIGHT]','[URL]');
    	$replace = array($img_width, $img_height, Carddeck::getDecksFolder().$file_path);
    	return str_replace($tpl_placeholder, $replace, self::$tpl_html);    	
    }
    
    public function isTradeable($include_hidden = true) {
        if(is_null($this->is_tradeable)){
            $this->is_tradeable = false;
            if($this->getStatus()->isTradeable() AND (!$include_hidden AND $this->getStatus()->isPublic())){
                $query = 'SELECT count(*) FROM '.Trade::getDbTableName().' WHERE (offered_card = '.$this->id.' OR requested_card = '.$this->id.') AND status = \'new\' ';
                $trades = $this->db->query($query)->fetchColumn();
                if($trades == 0){
                    $this->is_tradeable = true;
                }
            }
        }
        return $this->is_tradeable;
    }
    
    /**
     * checks if a card already exits
     * @param Member $member
     * @param Card $card
     * @param CardStatus $status
     * @return boolean
     */
    public static function existsInStatus($member,$card,$status) {
    	if(count(Card::getWhere(['owner'=>$member->getId(),'deck'=>$card->getDeckId(),'number'=>$card->getNumber(),'status_id'=>$status->getId()])) > 0){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    /**
     * creates a new card
     * @param Member $member might be NULL than no DB entry will be created
     * @param Int $deck_id
     * @param int $number
     * @param string $name optional
     * @return Card
     */
    public static function createNewCard($member,$deck_id,$number,$name=null){
    	$date = new DateTime('now');
    	$status_id = CardStatus::getNew()->getId();
    	$card = new Card();
    	if(is_null($name)){
    		$name = Carddeck::getById($deck_id)->getDeckname().$number;
    	}
    	$card->setPropValues(['deck'=>$deck_id,'number'=>$number,'name'=>$name,'status_id'=>$status_id,'utc'=>$date->format('c')]);
    	if($member instanceof Member){
    		$card->setOwner($member->getId());
    		$card->create();
    	}
    	return $card;
    }
    
    /**
     * Creates random Card objects 
     * @param Member $member - might be NULL than no DB entry will be created
     * @param int $amount
     * @throws ErrorException
     * @return Card[]
     */
    public static function createRandomCards($member,$amount){
    	$cards = array();
    	if(Carddeck::getCount(['status'=>'public']) > 0){
	    	if(is_int($amount) and $amount > 0){
	    		$decks = Carddeck::getRandom(true,$amount);
	    		foreach($decks as $deck){
	    			$deck_size = $deck->getSize();;
	    			$number = mt_rand(1,$deck_size);
	    			$cards[] = Card::createNewCard($member, $deck->getId(), $number);
	    		}
	    	}else{
	    		throw new ErrorException('second parameter needs to be a positiv integer');
	    	}
	    }else{
	    	throw new ErrorException('no public card decks found');
	    }
    	return $cards;
    }
    
    
    
    // TODO: Refactor - > move to update model class
    /**
     * @deprecated
     */
    public static function takeCardsFromUpdate($user_id,$update_id) {
        $cards = array();
        $db = Db::getInstance();
        $member = Member::getById($user_id);
         
        try{
            $update_decks = Carddeck::getInUpdate($update_id);
            $card_log_str = '';
            
            foreach($update_decks as $deck){
                // insert created data to insert into DB for a new Card Object
            	$number = mt_rand(1,$deck->getSize());
                $card_name = $deck->getDeckname().$number;
                
                $card = new Card();
                $card = Card::createNewCard($member, $deck->getId(), $number, $card_name);
                
                $cards[] = $card;
                $card_log_str.= ', '.$card->getName().' (#'.$card->getId().')';
            }
            if(count($cards) > 0){
                $req = $db->prepare('INSERT INTO updates_members (update_id, member_id) VALUES (:update_id,:user_id) ');
                $req->execute(array(':update_id'=>$update_id,':user_id'=>$user_id));
            }
            
            // add entry tradelog
            Tradelog::addEntry($member, '0','Cardupdate -> '.$card_log_str);
            
            
            $member->checkLevelUp();
            return $cards;
        }
        catch(Exception $e) {
            throw $e;
        }
        
    }
    
    /**
     * Get Member who owns a specific card with status thats tradeable
     * @param int $deck_id
     * @param int $number
     * @return Member[]
     */
    public static function findTrader($deck_id, $number){
        
        $db = Db::getInstance();
        $trader = array();
        
        $query = 'SELECT DISTINCT members.*, max(cards.id) as card_id FROM cards 
                JOIN members ON members.id = owner
                LEFT JOIN trades ON trades.status = \'new\' AND (requested_card = cards.id OR offered_card = cards.id)
				LEFT JOIN cards_stati ON cards_stati.id = status_id
                WHERE cards_stati.tradeable = \'1\' AND trades.status IS NULL AND deck= ? AND number = ?
                GROUP BY members.id 
                ORDER BY members.name ASC
                ';
        $req = $db->prepare($query);
        if($req->execute([$deck_id,$number])){
            foreach($req->fetchALL(PDO::FETCH_CLASS,'Member') as $member){
                
                $trader[$member->card_id] = $member; 
                
            }
        }
        
        return $trader;
    }
    
}