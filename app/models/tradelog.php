<?php
/**
 * 
 * Represents the Tradelog
 * 
 * @author NekoCari
 *
 */

class Tradelog extends DbRecordModel {
    
    protected $id, $date, $member_id, $sys_msg_code, $text;
    private static $query_order_by_options = array('id','date');
    private static $query_order_direction_options = array('ASC','DESC');
   
    protected static
        $db_table = 'tradelog',
        $db_pk = 'id',
        $db_fields = array('id','date','member_id','sys_msg_code','text'),
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
        
        // TODO: flush tradelog entries older than 3 months - move to login?
        $sql = "DELETE FROM ".self::$db_table." WHERE date < (DATE_SUB(CURDATE(), INTERVAL 3 MONTH))";
        
        
        if(!in_array($order_by, self::$sql_order_by_allowed_values)){
            $order_by = self::$sql_order_by_allowed_values[0];
        }
        if(!in_array($order, self::$sql_direction_allowed_values)){
            $order = self::$sql_direction_allowed_values[0];
        }
        
        return parent::getWhere('member_id = '.intval($member_id),[$order_by=>$order]);
    }
    
    /**
     * Add an entry to a members tradelog
     * 
     * @param int $member_id - id of member
     * @param string $text - text to add to entry
     * 
     * @return int
     */
    public static function addEntry($member_id, $sys_msg_code, $text=null) {
        $entry = new Tradelog();
        $entry->setPropValues(['member_id'=>$member_id,'sys_msg_code'=>$sys_msg_code,'text'=>$text]);
        return $entry->create();
    }
    
    /*
     *  basic GETTER methods 
     */
    
    public function getId() {
        return $this->id;
    }
    public function getDate($formated=true, $timezone=DEFAULT_TIMEZONE) {
    	$format = Setting::getByName('date_format')->getValue();
    	$date_time = new DateTime($this->date,new DateTimeZone($timezone));
        return $date_time->format($format);
    }
    public function getSystemMessageCode() {
    	return $this->sys_msg_code;
    }
    public function getSystemText($lang="de") {
    	$sys_msgs = SystemMessageTextHandler::getInstance();
    	return $sys_msgs->getTextByCode($this->getSystemMessageCode(),$lang);
    }
    public function getText() {
    	return $this->text;
    }
    public function getTextStripeId() {
        return preg_replace('/\(#[0-9]+\)/', ' ', $this->text);
    }
    
}