<?php
/**
 * 
 * Represents the Tradelog
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/setting.php';

class Tradelog extends DbRecordModel {
    
    protected $id, $date, $member_id, $text;
    private static $query_order_by_options = array('id','date');
    private static $query_order_direction_options = array('ASC','DESC');
   
    protected static
        $db_table = 'tradelog',
        $db_pk = array('id'),
        $db_fields = array('id','date','member_id','text'),
        $sql_order_by_allowed_values = array('id','date');
    
    public function __construct() {
       parent::__construct();
    }
    
    /**
     * get all log entries for a member by member_id
     * @param int $member_id
     * @param string $order_by
     * @param string $order
     * @return Tradelog[]
     */
    public static function getAllByMemberId($member_id, $order_by = 'date', $order = 'DESC') {
        
        // flush entries older than 3 months
        $sql = "DELETE FROM ".self::$db_table." WHERE date < (DATE_SUB(CURDATE(), INTERVAL 3 MONTH))";
        
        if(!in_array($order_by, self::$sql_order_by_allowed_values)){
            $order_by = self::$sql_order_by_allowed_values[0];
        }
        if(!in_array($order, self::$sql_direction_allowed_values)){
            $order = self::$sql_direction_allowed_values[0];
        }
        
        return parent::getWhere('member_id = '.intval($member_id),['date'=>'DESC']);
    }
    
    /**
     * Add an entry to a members tradelog
     * 
     * @param int $member_id - id of member
     * @param string $text - text to add to entry
     * 
     * @return int
     */
    public static function addEntry($member_id, $text) {
        $entry = new Tradelog();
        $entry->setPropValues(['member_id'=>$member_id,'text'=>$text]);
        return $entry->create();
    }
    
    /*
     *  basic GETTER methods 
     */
    
    public function getId() {
        return $this->id;
    }
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    public function getText() {
        return $this->text;
    }
    public function getTextStripeId() {
        return preg_replace('/\(#[0-9]+\)/', ' ', $this->text);
    }
    
}