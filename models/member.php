<?php
/**
 * 
 * Represents a Member
 * 
 * @author Cari
 *
 */

require_once PATH.'models/db_record_model.php';
require_once PATH.'models/card.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/master.php';
require_once PATH.'models/level.php';
require_once PATH.'models/member_right.php';
require_once PATH.'helper/Parsedown.php';

class Member extends DbRecordModel {
    
    protected 
        $id, $name, $mail, $info_text, $info_text_html, $level, $money, $join_date, $login_date, $status, $ip;
    
    private 
        $password, $cards, $masterd_decks;
    
        
    protected static
        $db_table = 'members',
        $db_pk = 'id',
        $db_fields = array('id','name','mail','info_text','info_text_html','level','money','join_date','login_date','status','ip'),
        $sql_order_by_allowed_values = array('id','name','level','join_date','login_date','status');
    
    private static $accepted_group_options = array('level');
    
    public function __construct() {
        parent::__construct();
    }
    
    
    /**
     * get data of all members in an array grouped by a col from database
     *
     * @param string $group fieldname for array grouping
     *
     * @return Member[]
     */
    public static function getGrouped($group, $order_by = 'name', $order = 'ASC') {
        
        $members = array();
        $db_conn = Db::getInstance();
        
        if(!in_array($order_by, self::$sql_order_by_allowed_values)){
            $order_by = self::$sql_order_by_allowed_values[0];
        }
        
        if(!in_array($order, self::$sql_direction_allowed_values)){
            $order = self::$sql_direction_allowed_values[0];
        }
        
        if(!in_array($group, self::$accepted_group_options)){
            $group = self::$accepted_group_options[0];
        }
        
        $members_array = self::getAll([$order_by=>$order]);
            
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
     * Uses Carddeck Method getMasterdByMember to get this members masterd decks
     * 
     * @return Carddeck[]
     */
    public function getMasteredDecks() {
        if(!isset($this->mastered_decks)){
            $this->mastered_decks = array();
            $masters = Master::getMasterdByMember($this->id);
            
            foreach($masters as $master){
                $this->mastered_decks[] = $master->getDeck(); 
            }
        }
        return $this->mastered_decks;
    }
    
    /**
     * returns url to view pofil
     *
     * @return string - html code 
     */
    public function getProfilLink() {
        return Routes::getUri('member_profil').'?id='.$this->id;
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
        return count($this->getMasteredDecks());
    }
    
    /**
     * returns the total amount of cards the member owns
     * 
     * @return int - number of cards in total
     */
    public function getCardCount(){
        $counter = 0;
        $counter+= count($this->getCardsByStatus('trade'));
        $counter+= count($this->getCardsByStatus('collect'));
        $counter+= count($this->getCardsByStatus('new'));
        $counter+= count($this->getCardsByStatus('keep'));
        return $counter;
    }
    
    /**
     * checks if the member has enough cards to level up and changes the level
     */
    public function checkLevelUp() {
        
        $current_level = $this->getLevel();
        $reached_level = Level::getByCardNumber($this->getCardCount());
        
        if(!is_null($reached_level) AND $current_level != $reached_level->getId()){
            
            $next_level = $this->getLevel('object')->next();
            
            if($next_level instanceof Level){
                
                $this->setLevel($next_level->getId());
                $this->cards = null;
                
                if($this->store()){
                    
                    // Gift for level up
                    $levelup_bonus = Setting::getByName('giftcards_for_master');
                    if($levelup_bonus instanceof Setting AND $levelup_bonus->getValue() > 0){
                        Card::createRandomCard($this->getId(),$levelup_bonus->getValue(),'Level Up!');
                    }
                    Message::add(null, $this->getId(), 'LEVEL UP! Du hast, für das Erreichen von Level '.$next_level->getName().', '.$levelup_bonus->getValue().' Karten erhalten.');
                    
                }
            }
        }
        
    }
    
    /**
     * returns the fields admins can edit
     * @return String[]
     */
    public function getEditableData($mode = 'user') {
        $filed = array();
        if($mode  == 'admin'){
            $fields = array('Name' => $this->name, 'Mail' => $this->mail, 'Level' => $this->level, 'Money' => $this->money, 'Text' => $this->info_text);
        }elseif($mode == 'user'){
            $fields = array('Name' => $this->name, 'Mail' => $this->mail, 'Text' => $this->info_text);
        }
        return $fields; 
    }
    
    /**
     * push current member data into database
     *
     * @return boolean
     */
    public function store() {
        
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
        $member_rights = MemberRight::getByMemberId($this->getId());
        $rights = array();
        foreach($member_rights as $right){
            $rights[] = $right->getRight();
        }
        return $rights;
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
    
    public function getInfoText() {
        return $this->info_text_html;
    }
    
    public function getInfoTextplain() {
        return $this->text;
    }
    
    
    /**
     * BASIC SETTER FUCTIONS
     */
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function setMail($mail) {
        $this->mail = $mail;
    }
    
    public function setLevel($level) {
        $this->level = $level;
    }    
    
    public function setMoney($amount){
        $this->money = intval($amount);
    }
    
    public function setInfoText($text) {
        $parsedown = new Parsedown();
        $this->info_text = $text;
        $this->info_text_html = $parsedown->text($this->info_text);
    }
    
    public function setPassword($pw){
        $req = $this->db->prepare('UPDATE members SET password = :password WHERE id = :id');
        return $req->execute([':password'=>password_hash($pw,PASSWORD_BCRYPT), ':id'=>$this->getId()]);
    }
    
    
}