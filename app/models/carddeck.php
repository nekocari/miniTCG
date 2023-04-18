<?php
/**
 * 
 * Represents a Carddeck
 * 
 * @author NekoCari
 *
 */

class Carddeck extends DbRecordModel {
    
    protected $id, $name, $deckname, $status, $type, $type_id, $creator, $date, $utc, $description, $description_html;
    
    protected static
    $db_table = 'decks',
    $db_pk = 'id',
    $db_fields = array('id','name','deckname','status','type','type_id','creator','date','utc','description','description_html'),
    $sql_order_by_allowed_values = array('id','name','deckname','date');
    
    private $creator_obj, $category_id, $subcategory_id, $category_obj, $subcategory_obj, $type_obj;
    private $date_formate = 'd.m.Y';
    
    private static $decks_folder;
    private static $naming_pattern = "/[A-Za-z0-9äÄöÖüÜß _\-]+/";
    private static $allowed_status = array('public','new'); 
    private static $allowed_types, $allowed_types_obj;
    
    public function __construct() {
        parent::__construct();
        $this->date_formate = Setting::getByName('date_format')->getValue();
    }
    
    public static function getAcceptedStati(){
        return self::$allowed_status;
    }
    
    /**
     * @return DeckType[]
     */
    public static function getAcceptedTypesObj(){
    	if(is_null(self::$allowed_types_obj)){
    		self::$allowed_types_obj = DeckType::getAll();
    	}
    	return self::$allowed_types_obj;
    }
    
    /**
     * 
     * @return string[]
     */
    public static function getAcceptedTypes(){
    	if(is_null(self::$allowed_types)){
    		foreach(self::getAcceptedTypesObj() as $type){
    			self::$allowed_types[$type->getId()] = $type->getName();
    		}
    	}
    	return self::$allowed_types;
    }
    
    public static function getDecksFolder() {
        if(is_null(self::$decks_folder)){
            $folder = Setting::getByName('cards_folder')->getValue();
            if(substr($folder, strlen($folder)-1,1) != '/'){
                $folder = $folder.'/';
            }
            self::$decks_folder = $folder;
        }
        return self::$decks_folder;
    }
    
    
    /**
     * get deck by id
     * @param int $id
     * @return Carddeck|NULL
     */
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    /**
     * Returns a Random Deck
     * @param boolean $public
     * @param int $amount
     * @return Carddeck[]
     */
    public static function getRandom($public=true,$amount=1) {
    	$decks = array();
    	$amount = max(1,intval($amount));
    	$sql = "SELECT * FROM ".self::$db_table;
    	if($public){
    		$sql.= " WHERE status = 'public' ";
    	}
		$sql.= "ORDER BY RAND() LIMIT $amount";
    	$db = DB::getInstance();
    	$req = $db->query($sql);
    	if($req->rowCount() > 0){
    		foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $deck){
    			$decks[] = $deck;
    		}
    	}
    	if(count($decks)<$amount){
    		$decks = array_merge($decks, self::getRandom($public,$amount-count($decks)));
    	}
    	return $decks;
    }
    
    /**
     * get all decks having a certain status
     * @param string $status
     * @param array $order
     * @throws Exception
     * @return Carddeck[]
     */
    public static function getAllByStatus($status = 'public',$order=['deckname'=>'ASC']) {
        if(!in_array($status,self::$allowed_status)){
            throw new Exception('Status enthält unerlaubten Wert.');
            $status = self::$allowed_status[0];
        }
        return parent::getWhere("status = '$status'",$order);
    }
    /**
     * 
     * @param int $sub_id id of Subcategory
     * @param string $status
     * @throws Exception
     * @return Carddeck[]
     */
    public static function getBySubcategory($sub_id,$status='public',$order=['deckname'=>'ASC']) {
        if(!in_array($status,self::$allowed_status)){
            throw new Exception('Status enthält unerlaubten Wert.');
            $status = self::$allowed_status[0];
        }
        $order_by = self::buildSqlPart('order_by',$order);
        
        $decks = array();
        $db = DB::getInstance();
        
        $query = 'SELECT d.*
                    FROM decks d
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                WHERE ds.subcategory_id = :sub and d.status = :status '.$order_by['query_part'];
        $req = $db->prepare($query);
        $req->execute(array(':sub'=>$sub_id,':status'=>$status));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $deck){
                $decks[] = $deck;
            }
        }
        return $decks;
    }
    
    /**
     * get all decks not related to an update
     * @return Carddeck[]
     */
    public static function getNotInUpdate() {
        $decks = array();
        $db = DB::getInstance();
        
        $query = 'SELECT d.*
                    FROM decks d
                    LEFT JOIN updates_decks ud ON ud.deck_id = d.id
                WHERE ud.update_id IS NULL ORDER BY d.id ASC';
        $req = $db->query($query);
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $deck){
                $decks[] = $deck;
            }
        }
        return $decks;
    }
    
    public static function getInUpdate($update_id) {
        $decks = array();
        $db = DB::getInstance();
        
        $query = 'SELECT d.*
                    FROM decks d
                    LEFT JOIN updates_decks ud ON ud.deck_id = d.id
                WHERE ud.update_id = :update_id ORDER BY d.id ASC';
        $req = $db->prepare($query);
        $req->execute(array(':update_id'=>$update_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $deck){
                $decks[] = $deck;
            }
        }
        return $decks;
    }
    
    public function getCollectorMembers(){
        $members = array();
        $req = $this->db->prepare('SELECT DISTINCT m.* FROM cards c JOIN members m ON m.id = c.owner WHERE deck = '.$this->id.' AND c.status_id = \''.CardStatus::getCollect()->getId().'\'');
        $req->execute();
        
        foreach($req->fetchAll(PDO::FETCH_CLASS,'Member') as $member){
            $members[] = $member;
        }
        
        return $members;
    }
    
    public static function master($deck, $member){
        require_once PATH.'models/setting.php';
        $db = DB::getInstance();
        
        $gift_cards_num = Setting::getByName('giftcards_for_master')->getValue();
        try{
            $db->beginTransaction();
            // delete cards
            $req = $db->prepare('DELETE FROM cards WHERE owner = :member AND deck = :deck AND status=\'collect\' ');
            $req->execute(array(':deck'=>$deck, ':member'=>$member));
            if($req->rowCount() != Setting::getByName('cards_decksize')->getValue()){
                throw new Exception('Das Deck ist nicht komplett!','');
            }
            // add master
            $req = $db->prepare('INSERT INTO decks_master (deck,member) VALUES (:deck,:member) ');
            $req->execute(array(':deck'=>$deck, ':member'=>$member));
            $db->commit();
            // add gift cards for mastering a deck
            if($gift_cards_num > 0){
                Card::createRandomCard($member,$gift_cards_num,'Deck gemastert');
            }
            return true;
        }
        catch(Exception $e) {
            $db->rollBack();
            return $e->getMessage();
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDeckname() {
        return $this->deckname;
    }
    
    public function getDate() {
        $date = date($this->date_formate, strtotime($this->date));
        return $date;
    }
    
    /**
     * 
     * @param string $mode [html|default]
     * @return string
     */
    public function getDescription($mode='html') {
        switch($mode){
            case 'html':
                if(empty($this->description_html) AND !empty($this->description)){
                    $parsedown = new Parsedown();
                    $this->description_html = $parsedown->text($this->description);
                    $this->update();
                }
                return $this->description_html;
                break;
            case 'default':
                return $this->description;
                break;
            default:
                return null;
                break;
        }
    }
    
    public function getCreator($mode = 'id') {
        if($mode == 'id'){
            return $this->creator;
        }else{
            return $this->getCreatorObj();
        }
    }
    
    public function getCreatorObj(){
        if(is_null($this->creator_obj)){
            $this->creator_obj = Member::getById($this->creator);
        }
        return $this->creator_obj;
    }
    
    public function getCreatorName() {
        if($this->getCreatorObj() instanceof Member){
            return $this->getCreatorObj()->getName();
        }
        return 'Unbekannt';
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getTypeId() {
    	return $this->type_id;
    }
    
    /**
     * returns type of current deck
     * @return DeckType|NULL
     */
    public function getType() {
    	if(!$this->type_obj instanceof DeckType){
    		$this->type_obj = DeckType::getById($this->getTypeId());	
    	}
    	return $this->type_obj;
    }
    
    public function getSize() {
    	return $this->getType()->getSize();
    }
    
    public function getCategory() {
        if(!$this->category_obj instanceof Category){
            $this->category_obj = $this->getSubcategory()->getCategory();
        }
        return $this->category_obj;
    }
    
    
    public function getSubcategory() {
        if(!$this->subcategory_obj instanceof Subcategory){
            $subcategory_relations = DeckSubcategory::getRelationsByDeckId($this->getId());
            if(count($subcategory_relations) == 1){
                $this->subcategory_obj = $subcategory_relations[0]->getSubcategory();
            }
        }
        return $this->subcategory_obj;
    }
    
    public function getCategoryName() {
        if($this->getCategory() instanceof Category){
            return $this->getCategory()->getName();
        }
        return 'Unbekannt';
    }
    
    public function getSubcategoryName() {
        if($this->getSubcategory() instanceof Subcategory){
            return $this->getSubcategory()->getName();
        }
        return 'Unbekannt';
    }
    
    public function getDeckpageUrl() {
        return Routes::getUri('deck_detail_page').'?id='.$this->id;
    }
    
    public function isPuzzle(){
        $is_puzzle = false;
        if($this->getType()->getName() == 'puzzle'){
            $is_puzzle = true;
        }
        return $is_puzzle;
    }
    
    public function getImageUrls() {
        $urls = array();
        $setting_file_type = Setting::getByName('cards_file_type')->getValue();
        $setting_cards_decksize = Setting::getByName('cards_decksize')->getValue();
        $deckname = $this->getDeckname();
        for($i = 1; $i <= $setting_cards_decksize; $i++){
            $urls[$i] = self::getDecksFolder().$deckname.'/'.$deckname.$i.'.'.$setting_file_type;
        }
        $urls['master'] = self::getDecksFolder().$deckname.'/'.$deckname.'_master.'.$setting_file_type;
        return $urls;
    }
    
    public function getImages() {
        $card_images = array();
        $setting_tpl_width = Setting::getByName('cards_template_width')->getValue();
        $setting_tpl_height = Setting::getByName('cards_template_height')->getValue();
        $setting_master_tpl_width = Setting::getByName('cards_master_template_width')->getValue();
        $setting_master_tpl_height = Setting::getByName('cards_master_template_height')->getValue();
        $cardimage_tpl = file_get_contents(PATH.'app/views/multilang/templates/card_image_temp.php');
        
        $image_urls = $this->getImageUrls();
        
        $tpl_placeholder = array('[WIDTH]','[HEIGHT]','[URL]');
        
        foreach($image_urls as $key=>$url){
            if($key != 'master'){
                $replace = array($setting_tpl_width, $setting_tpl_height, $url);
            }else{
                $replace = array($setting_master_tpl_width, $setting_master_tpl_height, $url);
            }
            $card_images[$key] = str_replace($tpl_placeholder, $replace, $cardimage_tpl);
        }
        return $card_images;
    }
    
    public function getDeckView() {
    	$deck = '';
    	$card_images = $this->getImages();
    	unset($card_images['master']);
    	
    	if(empty($this->getType()->getTemplatePath())){
	        $cards_per_row = Setting::getByName('deckpage_cards_per_row')->getValue();
	        $decksize = $this->getType()->getSize();
	        $counter = 0;
	        foreach($card_images as $image){
	            $counter++;
	            $deck.= $image;
	            if($counter%$cards_per_row == 0 AND $counter != $decksize){
	                $deck.= "<br>";
	            }
	        }
    	}else{
    		$template_path = PATH.'app/views/'.$this->getType()->getTemplatePath();
    		if(file_exists($template_path)){
    			$template = file_get_contents($template_path);
    			$counter = 1;
    			foreach($card_images as $image){
    				$template = str_replace('['.$counter.']', $image, $template);
    				$counter++;
    			}
    			$deck = $template;
    		}
    	}
    	return $deck;
    }
    
    public function getMasterCard() {
        $card_images = $this->getImages();
        return $card_images['master'];
    }
    
    public function getMasterMembers() {
        return Master::getMemberByDeck($this->id);
    }
    
    
}