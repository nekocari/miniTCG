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
    
    private $id;
    private $name;
    private $level;
    private $mail;
    private $join_date;
    private $cards;
    private $mastered_decks;
    private static $query_order_by_options = array('id','name','level');
    private static $query_order_direction_options = array('ASC','DESC');
    private static $accepted_group_options = array('id','level');
    
    public function __construct($id, $name, $level, $mail, $join_date='00.00.0000 00:00:00') {
        $this->id = $id;
        $this->name = $name;
        $this->level = $level;
        $this->mail = $mail;
        $this->join_date = $join_date;
    }
    
    /**
     * get data of all members from database
     *
     * @param Db $db_conn PDO connection object
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
                $members[] = new Member($data['id'], $data['name'], $data['level'], $data['mail'], $data['join_date']);
            }
        }
        
        return $members;
    }
    
    /**
     * get data of all members from database
     *
     * @param Db $db_conn PDO connection object
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
        
        $req = $db_conn->query("SELECT id, name, level, mail FROM members ORDER BY $order_by $order");
        if($req->execute()){
            foreach($req->fetchAll() as $data) {
                $members[$data[$group]][] = new Member($data['id'], $data['name'], $data['level'], $data['mail']);
            }
        }
        
        return $members;
    }
    
    /**
     * get member data from database using id number
     * 
     * @param int $id Id number of member in database
     * @param Db $db_conn PDO connection object
     * 
     * @return boolean|Member 
     */
    public static function getById($id) {
        $member = false;
        $db_conn = Db::getInstance();
        
        $req = $db_conn->prepare('SELECT * FROM members WHERE id = :id');
        if($req->execute(array(':id' => $id))) {
            if($req->rowCount()) {
                $data = $req->fetch();
                $member = new Member($data['id'], $data['name'], $data['level'], $data['mail'], $data['join_date']);
            }
        }
        
        return $member;
    }
    
    /**
     * push current member data into database
     * 
     * @return boolean|string retuns true or in case of failure the Excetion message
     */
    public function store() {
        $db = Db::getInstance();
        try{
            $req = $db->prepare('UPDATE members SET name = :name, level = :level, mail = :mail WHERE id = :id');
            $req->execute(array(':id' => $this->id, ':name' => $this->name, ':level' => $this->level, ':mail' => $this->mail, ));
            return true;
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }
    
    
    /**
     * Uses Card Method getMemberCardsByStatus to get this members cards of given status
     * 
     * @param string $status current card status
     * 
     * @return Card[] array of Card Objects or empty array 
     */
    public function getCardsByStatus($status) {
        if(!isset($this->cards[$status])){
            $this->cards[$status] = Card::getMemberCardsByStatus($this->id, $status);
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
     * @return string
     */
    public function getProfilLink() {
        return 'members/profil.php?id='.$this->id;
    }
    
    public function gotUpdateCards($update_id){
        $db = Db::getInstance();
        $req = $db->prepare('SELECT * FROM updates_members WHERE member_id = :member_id AND update_id = :update_id ');
        $req->execute(array(':member_id'=>$this->id, ':update_id'=>$update_id));
        if($req->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function getMasterCount(){
        return count($this->getMasteredDecks());
    }
    
    public function getCardCount(){
        $counter = 0;
        $counter+= count($this->getCardsByStatus('trade'));
        $counter+= count($this->getCardsByStatus('collect'));
        $counter+= count($this->getCardsByStatus('new'));
        $counter+= count($this->getCardsByStatus('keep'));
        return $counter;
    }
    
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
}