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
    
    public function getAllByMemberId($member_id, $order_by = 'date', $order = 'DESC') {
        $entries = false;
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'date';
        }
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'DESC';
        }
        $req = $this->db->prepare('SELECT * FROM tradelog WHERE member_id = :member '.$order_by.' '.$order);
        $req->execute(array(':member'=>$member_id));
        
        foreach($req->fetchAll(PDO::FETCH_ASSOC) as $entry){
            $entry['date'] = date(Setting::getByName('date_format')->getValue(),strtotime($entry['date']));
            $entries[] = new Tradelog($entry['id'], $entry['date'], $entry['member'], $entry['text']);
        }
        return $entries;
    }
    
    public static function addEntry($member_id, $text) {
        $db = DB::getInstance();
        $req = $db->prepare('INSERT INTO tradelog (member_id,text) VALUES (:member, :text)');
        $req->execute(array(':member'=>$member_id, ':text'=>$text));
    }
   
}