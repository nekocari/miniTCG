<?php
/**
 * 
 * Represents a Member
 * 
 * @author NekoCari
 *
 */

class Member extends DbRecordModel {
    
    protected 
        $id, $name, $mail, $info_text, $info_text_html, $level, $money, $join_date, $login_date, $status, $ip;
    
    private 
        $password, $cards, $masterd_decks, $rights, $profil_cards, $tradeable_cards, $settings;
    
        
    protected static
        $db_table = 'members',
        $db_pk = 'id',
        $db_fields = array('id','name','mail','info_text','info_text_html','level','money','join_date','login_date','status','ip'),
        $sql_order_by_allowed_values = array('id','name','level','join_date','login_date','status');
    
        private static 
            $accepted_group_options = array('level'),
            $accepted_status_options = array('pending','default','suspended');
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public static function getAcceptedStati(){
        return self::$accepted_status_options;
    }
    
    /**
     * get data of all members in an array grouped by a col from database
     *
     * @param string $group fieldname for array grouping
     *
     * @return Member[]
     */
    public static function getGrouped($group, $only_active = true, $order=['name' => 'ASC']) {
        
        $members = array();
        
        if(!in_array($group, self::$accepted_group_options)){
            $group = self::$accepted_group_options[0];
        }
        
        if($only_active){
        	$members_array = self::getWhere(['status'=>'default'],$order);
        }else{
        	$members_array = self::getAll($order);
        }
            
        foreach($members_array as $member){
            $members[$member->$group][]  = $member;
        }
        
        return $members;
    }
    
    /**
     * get member data from database using id number
     *
     * @param int $id Id number of member in database
     *
     * @return boolean|Member
     */
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    /**
     * get member data from database mail adress
     *
     * @param string $mail
     *
     * @return Member|NULL
     */
    public static function getByMail($mail) {
        return parent::getByUniqueKey('mail', $mail);
    } 
    
    /**
     * find members with names matching the search string
     * 
     * @param string $search_str
     * 
     * @return Member[]
     */
    public static function searchByName($search_str) {
        
        $db = DB::getInstance();
        $members = array();
        
        $req = $db->prepare('SELECT * FROM '.self::$db_table.' WHERE name LIKE :search_str ');
        $req->execute(array(':search_str'=>'%'.$search_str.'%'));
       
            foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $member){
                $members[] = $member;
            }
            
        return $members;
    }
    
    /**
     * 
     * @return string
     */
    public function getPassword(){
    	return $this->password;
    }
    
    public function getStatus() {
    	return $this->status;
    }
    
    /**
     * 
     * @return MemberSetting|NULL
     */
    public function getSettings() {
    	if(!$this->settings instanceof MemberSetting){
    		$this->settings = MemberSetting::getByMemberId($this->getId());
    	}
    	return $this->settings;
    }
    
    
    public function getLang() {
    	return $this->getSettings()->getValueByName('language');
    }
    public function getTimezone() {
    	return $this->getSettings()->getValueByName('timezone');
    }
    
    /**
     * Uses Card Method getMemberCardsByStatus to get this members cards of given status
     * 
     * @param string $status current card status
     * 
     * @return Card[] array of Card Objects or empty array 
     */
    public function getCardsByStatus($status, $only_tradeable = false) {
        if(!isset($this->cards[$status])){
            $this->cards[$status] = Card::getMemberCardsByStatus($this->id, $status, $only_tradeable);
        }
        return $this->cards[$status];
    }
    
    /**
     * retuns an array of flaggable cards
     * @param int $status_id
     * @param boolean $only_tradeable
     * @return CardFlagged[]
     */
    public function getProfilCardsByStatus($status_id, $only_tradeable = true){
    	if(!isset($this->profil_cards[$status_id])){
    		$this->profil_cards[$status_id] = CardFlagged::getMemberCardsByStatus($this->getId(), $status_id, true);
    	}
    	return $this->profil_cards[$status_id];
    }
    
    /**
     * retuns an array of flaggable cards
     * @param int $status_id
     * @param boolean $only_tradeable
     * @return CardFlagged[]
     */
    public function getTradeableCards($flag_for_member_id = NULL){
    	if(!is_null($flag_for_member_id) AND (!is_array($this->tradeable_cards) OR !$this->tradeable_cards[0] instanceof CardFlagged)){
    		$this->tradeable_cards = null;
    		foreach(CardFlagged::getMemberCardsTradeable($this->getId()) as $card){
	    		$this->tradeable_cards[] = $card->flag($flag_for_member_id);
    		}
    	}elseif(is_null($flag_for_member_id) AND (!is_array($this->tradeable_cards) OR !$this->tradeable_cards[0] instanceof Card)){
    		$this->tradeable_cards = Card::getMemberCardsTradeable($this->getId());
    	}
    	return $this->tradeable_cards;
    }
    
    /**
     * @todo make work with status objects
     * @param string $status
     * @return NULL[][]|unknown[][]
     */
    public function getDuplicateCards($tradeable = true){
        return Card::getDuplicatesByMemberId($this->getId(),$tradeable);
    }
    
    /**
     * Uses Carddeck Method getMasterdByMember to get this members masterd decks
     * 
     * @return Carddeck[]
     */
    public function getMasteredDecks($grouped = false) {
        if(!isset($this->mastered_decks)){
            $this->mastered_decks = array();
            $masters = Master::getMasterdByMember($this->id,$grouped);
            $this->mastered_decks = $masters;
        }
        return $this->mastered_decks;
    }
    
    /**
     * returns url to view pofil
     *
     * @return string - html code
     */
    public function getProfilLink() {
    	if(!is_null($this->id)){
    		return Routes::getUri('member_profil').'?id='.$this->id;
    	}else{
    		return null;
    	}
    }
    
    /**
     * returns linked member name
     *
     * @return string - html code
     */
    public function getMemberLinkHtml() {
    	if(!is_null($url = $this->getProfilLink())){
    		return '<a href="'.$url.'">'.$this->getName().'</a>';
    	}else{
    		return $this->getName();
    	}
    }
    
    /**
     * checks if member already took cards from the update with $update_id
     * 
     * @param int $update_id - id of update
     * 
     * @return boolean
     */
    public function gotUpdateCards($update_id){
        $req = $this->db->prepare('SELECT * FROM updates_members WHERE member_id = :member_id AND update_id = :update_id ');
        $req->execute(array(':member_id'=>$this->id, ':update_id'=>$update_id));
        if($req->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * retuns the amount of decks the member has mastered
     * 
     * @return int
     */
    public function getMasterCount(){
        $master_counter = 0;
        if(!is_null($this->masterd_decks)){
            $master_counter = count($this->getMasteredDecks());
        }else{
            $sql = "SELECT COUNT(*) FROM ".Master::getDbTableName()." WHERE member = ".$this->getId();
            $req = $this->db->query($sql);
            $master_counter = $req->fetchColumn();
        }
        return $master_counter;
    }
    
    /**
     * returns the total amount of cards the member owns
     * 
     * @return int - number of cards in total
     */
    public function getCardCount(){
        $sql = "SELECT COUNT(*) FROM ".Card::getDbTableName()." WHERE owner = ".$this->getId();
        $req = $this->db->query($sql);
        $counter = $req->fetchColumn();
        return $counter;
    }
    
    /**
     * checks if the member has enough cards to level up and changes the level
     * TODO: master card count individual by deck size!
     */
    public function checkLevelUp() {
    	
    	$sql = 'SELECT SUM(decks_types.size) FROM decks_master 
				LEFT JOIN decks ON decks_master.deck = decks.id
				LEFT JOIN decks_types ON decks_types.id = decks.type_id
				WHERE member = '.$this->getId();
    	$req = $this->db->query($sql);
    	$master_cards_sum = $req->fetchColumn();
    	if(is_null($master_cards_sum)){ $master_cards_sum = 0; }
    	
    	$card_count_with_master = $this->getCardCount() + $master_cards_sum;
        $current_level = $this->getLevel('object');
        $reached_level = Level::getByCardNumber($card_count_with_master);
        //var_dump($reached_level).'<hr>'.var_dump($reached_level);
        if(!is_null($reached_level) AND $current_level->getLevel() != $reached_level->getLevel()){
            
            $next_level = $this->getLevel('object')->next();
            
            if($next_level instanceof Level){
                
                $this->setLevel($next_level->getId());
                $this->cards = null;
                
                if($this->update()){
                    
                    // Gift for level up
                    $levelup_bonus = Setting::getByName('master_gift_cards');
                    if($levelup_bonus instanceof Setting AND $levelup_bonus->getValue() > 0){
                        $cards = Card::createRandomCards($this, intval($levelup_bonus->getValue()));
                        $cardnames = $cardnames_msg = '';
                        foreach($cards as $card){
                        	$cardnames_msg.= $card->getName()." (#".$card->getId()."), ";
                        	$cardnames.= $card->getName()." (#".$card->getId()."), ";
                        }
                        $cardnames = substr($cardnames, 0, -2);
                        Tradelog::addEntry($this, 'level_up_info',$cardnames);
                        $msg_text = SystemMessageTextHandler::getInstance()->getTextByCode('level_up_info',$this->getLang());
                        Message::add(null, $this->getId(), $msg_text.$cardnames_msg);
                    }
                    
                }
            }
        }
        
    }
    
    /**
     * returns the fields admins can edit
     * @return String[]
     */
    public function getEditableData($login = null) {
        $fields = array();
        if($login instanceof Login AND count($login->getUser()->getRights()) > 0){
            $fields = array('Name' => $this->name, 'Mail' => $this->mail, 'Status' => $this->status, 'Level' => $this->level, 'Money' => $this->money, 'Text' => $this->info_text);
        }else{
            $fields = array('Name' => $this->name, 'Mail' => $this->mail, 'Text' => $this->info_text);
        }
        return $fields; 
    }
    
    /**
     * @deprecated
     * push current member data into database
     *
     * @return boolean
     */
    public function store() {
    	$this->update();
    }
    
    public function update() {
    	// make sure info_text_html is up to date!
    	$parsedown = new Parsedown();
    	$this->info_text_html = $parsedown->text($this->info_text);
    	
    	return parent::update();
    }
    
    
    /**
     * get members assigned rights
     * @return Right[]
     */
    public function getRights(){
        if(is_null($this->rights)){
            $member_rights = MemberRight::getByMemberId($this->getId());
            $this->rights = array();
            foreach($member_rights as $right){
                $this->rights[] = $right->getRight()->getName();
            }
        }
        return $this->rights;
    }
    
    /**
     * add a right to member
     *
     * @param int $right_id - id of right to add
     *
     * @return boolean
     */
    public function addRight($right_id){
        $member_right = new MemberRight();
        $member_right->setPropValues(['member_id'=>$this->getId(), 'right_id'=>$right_id]);
        try {
            $member_right->create();
            return true;
        }
        catch(Exception $e){
            return false;
        }
        
    }
    
    /**
     * remove a right from member
     *
     * @param int $right_id - id of right to remove
     *
     * @return boolean
     */
    public function removeRight($right_id){
        $result = false;
        try{
            $member_right = MemberRight::getWhere('member_id = '.$this->getId().' AND right_id = '.intval($right_id));
            if(is_array($member_right) AND count($member_right) == 1){
                $member_right[0]->delete();
            }
        }
        catch(Exception $e){
            return false;
        }
    }
    
    /**
     * adds set amount to member money balance
     * @param int $amount - has to be positive!
     * @throws ErrorException
     * @return boolean
     */
    public function addMoney($amount){
        if(is_numeric($amount) AND intval($amount) > 0){
            $this->setMoney($this->getMoney() + intval($amount));
            return true;
        }else{
            throw new ErrorException('amount has to be a positiv number');
            return false;
        }
    }
    
    /**
     * @todo
     * @throws ErrorException
     * @return boolean
     */
    public function resetPassword(){
    	
    	$login = new Login;
    	$random_code = $login->getRandomActivationCode();            
        
    	if($this->setPassword($random_code)){
    		// send mail with code
    		$app_name = Setting::getByName('app_name')->getValue();
    		$app_mail = Setting::getByName('app_mail')->getValue();
    		$name = $this->getName();
    		$mail  = $this->getMail();
    		
    		$subject = $app_name;
    		$message = file_get_contents('app/views/'.$this->getLang().'/templates/mail_template_reset_pw.php');
    		$message = str_replace(['{{MEMBERNAME}}','{{PASSWORD}}','{{APPNAME}}'], [$name,$random_code,$app_name], $message);
    		
    		// mail header
    		$header[] = 'MIME-Version: 1.0';
    		$header[] = 'Content-type: text/html; charset=UTF-8';
    		$header[] = 'From: '.$app_name.' <'.$app_mail.'>';
    		
    		// send mail
    		error_reporting(0);
    		if(!mail($mail, $subject, $message, implode("\r\n", $header))){
    			throw new ErrorException('Unable to send new password');
    		}
    		error_reporting(-1);
    		
    		return true;
        }else{
        	return false;
        }
    }
    
    
    
    /**
     * BASIC GETTER FUNCTIONS
     */
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getMail() {
        return $this->mail;
    }
    
    /**
     * @param string $mode [id|obj]
     * @return int|Level|NULL
     */
    public function getLevel($mode='id') {
        if($mode=='id'){
            return $this->level;
        }else{
            return Level::getById($this->level);
        }
    }
    
    public function getMoney() {
        return $this->money;
    }
    
    public function getJoinDate() {
        return $this->join_date;
    }
    
    public function getInfoText($mode='html') {
        $text = '';
        switch($mode){
            case 'html':
                $text = $this->info_text_html;
                break;
            default:
                $text = $this->info_text;
                break;
        }
        return $text;
    }
    
    /**
     * @deprecated
     */
    public function getInfoTextplain() {
        return $this->text;
    }
    
    
    /**
     * BASIC SETTER FUCTIONS
     */
    
    public function setName($name) {
        $this->name = $name;
        return true;
    }
    
    public function setMail($mail) {
        $this->mail = $mail;
        return true;
    }
    
    public function setLevel($level) {
        $this->level = $level;
        return true;
    }    
    
    public function setMoney($amount){
        $this->money = intval($amount);
        return true;
    }
    
    public function setStatus($status){
        if(in_array($status, self::$accepted_status_options)){
            $this->status = $status;
            return true;
        }else{
            return false;
        }
    }
    
    public function setInfoText($text) {
        $parsedown = new Parsedown();
        $this->info_text = strip_tags($text);
        $this->info_text_html = $parsedown->text($this->info_text);
        if($this->info_text != $text){
            throw new Exception('Unerlaubte Elemente wie HTML Code wurden entfernt.','8000');
        }
        return true;
    }
    
    public function setPassword($pw){
    	$this->password = password_hash($pw, PASSWORD_DEFAULT, ['cost'=>10]);
    	$req = $this->db->prepare('UPDATE '.self::$db_table.' SET password = :password WHERE id = :id ');
    	$req->execute([':password'=>$this->getPassword(),':id'=>$this->getId()]);
    	if($req->rowCount() != 0){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    public function updatePassword($pw1,$pw2){
    	if($pw1 === $pw2){
    		return $this->setPassword($pw1);
    	}
    }
    
    
}