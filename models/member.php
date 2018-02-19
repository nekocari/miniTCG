<?php
/**
 * 
 * Represents a Member
 * 
 * @author Cari
 *
 */
class Member {
    
    public $id;
    public $name;
    public $level;
    public $mail;
    private static $query_order_by_options = array('id','name','level');
    private static $query_order_direction_options = array('ASC','DESC');
    
    public function __construct($id, $name, $level, $mail) {
        $this->id = $id;
        $this->name = $name;
        $this->level = $level;
        $this->mail = $mail;
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
    public static function getAll($db_conn, $order_by, $order) {
        $members = array();
        
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'name';
        }
        
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'ASC';
        }
        
        $req = $db_conn->query("SELECT id, name, level, mail FROM members ORDER BY $order_by $order");
        if($req->execute()){
            foreach($req->fetchAll() as $data) {
                $members[] = new Member($data['id'], $data['name'], $data['level'], $data['mail']);
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
    public static function getById($id, $db_conn) {
        $member = false; 
        
        $req = $db_conn->prepare('SELECT id, name, level, mail FROM members WHERE id = :id');
        if($req->execute(array(':id' => $id))){
            $data = $req->fetch();
            $member = new Member($data['id'], $data['name'], $data['level'], $data['mail']);
        }
        
        return $member;
    }
}