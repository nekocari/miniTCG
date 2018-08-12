<?php
/**
 * 
 * Represents the Tradelog
 * 
 * @author Cari
 *
 */
require_once PATH.'models/setting.php';
class Tradelog {
    
    private $id;
    private $date;
    private $member_id;
    private $text;
    private $db;
    private static $query_order_by_options = array('id','date');
    private static $query_order_direction_options = array('ASC','DESC');
    
    public function __construct($id, $date, $member_id, $text) {
        $this->id           = $id;
        $this->date         = $date;
        $this->member_id    = $member_id;
        $this->text         = $text;
        $this->db           = DB::getInstance();
    }
    
    /**
     * get all log entries for a member using the member ID
     * 
     * @param int $member_id - id of member
     * @param string $order_by - colum to order by [id|date]
     * @param string $order - [ASC|DESC]
     * 
     * @return boolean|Array(Tradelog)
     */
    public static function getAllByMemberId($member_id, $order_by = 'date', $order = 'DESC') {
        $entries = false;
        $db = DB::getInstance();
        
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'date';
        }
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'DESC';
        }
        $req = $db->prepare('SELECT * FROM tradelog WHERE member_id = :member ORDER BY '.$order_by.' '.$order);
        $req->execute(array(':member'=>$member_id));
        
        foreach($req->fetchAll(PDO::FETCH_ASSOC) as $entry){
            $entry['date'] = date(Setting::getByName('date_format')->getValue(),strtotime($entry['date']));
            $entries[] = new Tradelog($entry['id'], $entry['date'], $entry['member_id'], $entry['text']);
        }
        return $entries;
    }
    
    /**
     * Add an entry to a members tradelog
     * 
     * @param int $member_id - id of member
     * @param string $text - text to add to entry
     * 
     * @return boolean
     */
    public static function addEntry($member_id, $text) {
        $db = DB::getInstance();
        $req = $db->prepare('INSERT INTO tradelog (member_id,text) VALUES (:member, :text)');
        return $req->execute(array(':member'=>$member_id, ':text'=>$text));
    }
    
    /*
     *  basic GETTER methods 
     */
    
    public function getId() {
        return $this->id;
    }
    public function getDate() {
        return $this->date;
    }
    public function getText() {
        return $this->text;
    }
    public function getTextStripeId() {
        return preg_replace('/\(#[0-9]+\)/', ' ', $this->text);
    }
    
}