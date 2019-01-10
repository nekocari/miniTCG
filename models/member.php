<?php
/**
 * 
 * Represents a Member
 * 
 * @author Cari
 *
 */

require_once PATH.'models/card.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/master.php';

class Member {
    
    protected 
        $db, $id, $name, $level, $mail, $join_date, $text, $text_html, $cards,  $mastered_decks;
    private static 
        $query_order_by_options = array('id','name','level'),
        $query_order_direction_options = array('ASC','DESC'),
        $accepted_group_options = array('id','level');
    
    public function __construct($id, $name, $level, $mail, $join_date, $text, $text_html) {
        $this->id = $id;
        $this->name = $name;
        $this->level = $level;
        $this->mail = $mail;
        $this->join_date = $join_date;
        $this->text = $text;
        $this->text_html = $text_html;
        $this->db = Db::getInstance();
    }
    
    /**
     * get data of all members from database
     *
     * @param string $order_by fieldname for order [id|name|level]
     * @param string $order order direction [ASC|DESC]
     *
     * @return Member[]
     */
    public static function getAll($order_by, $order) {
        $members = array();
        $db_conn = Db::getInstance();
        
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'name';
        }
        
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'ASC';
        }
        
        $req = $db_conn->query("SELECT * FROM members ORDER BY $order_by $order");
        if($req->execute()){
            foreach($req->fetchAll() as $data) {
                $members[] = new Member($data['id'], $data['name'], $data['level'], $data['mail'], $data['join_date'], $data['info_text'], $data['info_text_html']);
            }
        }
        
        return $members;
    }
    
    /**
     * get data of all members from database
     *
     * @param string $group fieldname for array grouping
     *
     * @return Member[]
     */
    public static function getGrouped($group, $order_by = 'id', $order = 'ASC') {
        $members = array();
        $db_conn = Db::getInstance();
        
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'name';
        }
        
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'ASC';
        }
        
        if(!in_array($group, self::$accepted_group_options)){
            $group = 'id';
        }
        
        $req = $db_conn->query("SELECT * FROM members ORDER BY $order_by $order");
        if($req->execute()){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $data) {
                $members[$data->$group][] = new Member($data->id, $data->name, $data->level, $data->mail, $data->join_date, $data->info_text, $data->info_text_html);
            }
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
        $member = false;
        $db_conn = Db::getInstance();
        
        $req = $db_conn->prepare('SELECT * FROM members WHERE id = :id');
        if($req->execute(array(':id' => $id))) {
            if($req->rowCount() == 1) {
                $data = $req->fetch(PDO::FETCH_OBJ);
                $member = new Member($data->id, $data->name, $data->level, $data->mail, $data->join_date, $data->info_text, $data->info_text_html);
            }
        }
        
        return $member;
    }
    
    /**
     * get member data from database using id number
     *
     * @param int $id Id number of member in database
     *
     * @return boolean|Member
     */
    public static function getByMail($mail) {
        $member = false;
        $db_conn = Db::getInstance();
        
        $req = $db_conn->prepare('SELECT * FROM members WHERE mail = :mail');
        if($req->execute(array(':mail' => $mail))) {
            if($req->rowCount() == 1) {
                $data = $req->fetch(PDO::FETCH_OBJ);
                $member = new Member($data->id, $data->name, $data->level, $data->mail, $data->join_date, $data->info_text, $data->info_text_html);
            }
        }
        
        return $member;
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
        
        $req = $db->prepare('SELECT * FROM members WHERE name LIKE :search_str ');
        $req->execute(array(':search_str'=>'%'.$search_str.'%'));
        foreach($req->fetchAll(PDO::FETCH_OBJ) as $data){
            $members[] = new Member($data->id, $data->name, $data->level, $data->mail, $data->join_date, $data->info_text, $data->info_text_html);
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
        require_once PATH.'models/level.php';
        $reached_level = Level::getByCardNumber($this->getCardCount());
        
        if($this->getLevel() != $reached_level->getId()){
            
            // find next level id and update member data
            $next_level = Level::getById($this->getLevel())->next();
            if($next_level != false){
                
                $this->setLevel($next_level->getId());
                $this->store();
                
                // TODO: gift for level up??
                if($next_level != $reached_level){
                    $this->checkLevelUp();
                }
            }
        }
    }
    
    /**
     * returns the fields admins can edit
     * @return String[]
     */
    public function getEditableData($mode = 'admin') {
        if($mode  = 'admin'){
            $fields = array('Name' => $this->name, 'Mail' => $this->mail, 'Level' => $this->level, 'Text' => $this->text);
        }else{
            $fields = array('Name' => $this->name, 'Mail' => $this->mail, 'Text' => $this->text);
        }
        return $fields; 
    }
    
    /**
     * push current member data into database
     *
     * @return boolean|string retuns true or in case of failure the Excetion message
     */
    public function store() {
        try{
            $req = $this->db->prepare('UPDATE members SET name = :name, level = :level, mail = :mail , info_text = :text, info_text_html = :text_html WHERE id = :id');
            $req->execute(array(':id' => $this->id, ':name' => $this->name, ':level' => $this->level, ':mail' => $this->mail, ':text' => $this->text, ':text_html' => $this->text_html) );
            return true;
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * add a right to member
     *
     * @param int $right_id - id of right to add
     *
     * @return boolean
     */
    public function addRight($right_id){
        $req = $this->db->prepare('INSERT INTO members_rights (member_id,right_id) VALUES (:member_id,:right_id) ');
        if($req->execute(array(':member_id'=>$this->id, ':right_id'=>$right_id))){
            return true;
        }else{
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
        $req = $this->db->prepare('DELETE FROM members_rights WHERE member_id = :member_id AND right_id = :right_id ');
        if($req->execute(array(':member_id'=>$this->id, ':right_id'=>$right_id))){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Deletes a Member entierly from Database
     * 
     * @return boolean
     */
    public function delete(){
        $req = $this->db->query('DELETE FROM members WHERE id = '.$this->id);
        if($req->rowCount() == 1){
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
    
    public function getLevel() {
        return $this->level;
    }
    
    public function getJoinDate() {
        return $this->join_date;
    }
    
    public function getInfoText() {
        return $this->text_html;
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
    
    public function setInfoText($text) {
        $parsedown = new Parsedown();
        $this->text = $text;
        $this->text_html = $parsedown->text($this->text);
    }
    
    
}