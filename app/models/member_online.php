<?php
/**
 * Represents a MemberOnline
 * 
 * @author NekoCari
 * 
 */


class MemberOnline extends DbRecordModel {
    
    protected $member_id, $date, $utc, $ip, $member;
    protected static
        $db_table = 'members_online',
        $db_pk = 'member_id',
        $db_fields = array('member_id','date','utc','ip'),
        $sql_order_by_allowed_values = array('member_id','date');
        
    private static $del_timer_min = 5; // in minutes
        
    public function __construct() {
        parent::__construct();
    }
    
    public function getMemberId(){
        return $this->member_id;
    }
    
    public function getDate($timezone=DEFAULT_TIMEZONE){
    	$date = new DateTime($this->utc);
    	$date->setTimezone(new DateTimeZone($timezone));
    	return $date->format(Setting::getByName('date_format')->getValue());
    }
    
    public function getMember(){
        if(is_null($this->member)){
            $this->member = Member::getById($this->member_id);
        }
        return $this->member;
    }
    
    
    /**
     * @todo - visibility!
     * returns only users that allow beeing displayed
     * @param mixed[] $order_settings
     * @param int|int[] $limit
     * @return MemberOnline[]
     */
    public static function getVisible($viewer=null, $order_settings=null, $limit=null) {
    	$members = self::getAll($order_settings,$limit);
    	/*
    	foreach($members as $key => $mo){
    		if(!$mo->getMember()->isVisibleOnline() OR ($viewer instanceof Member AND $mo->getMember()->hasBlacklisted($viewer))){
    			unset($members[$key]);
    		}
    	}*/
    	return $members;
    }
    
    
    /**
     * @todo
     * @return mixed
     */
    public static function getInvisible() {
    	$db = Db::getInstance();
    	$req = $db->query("SELECT count(*) FROM ".self::getDbTableName()." JOIN ".MemberSetting::getDbTableName()." USING (member_id) WHERE online_status_visible = 0");
    	return $req->fetchColumn();
    }
    
    /**
     * get record of online user by $id
     * @param int $id
     * @return MemberOnline|NULL
     */
    public static function getByMemberId($id) {
        return parent::getByPk($id);
    }
    
    public static function truncate(){
    	$db = DB::getInstance();
    	$db->query('DELETE FROM '.self::$db_table.' WHERE date < DATE_SUB(NOW(), INTERVAL '.self::$del_timer_min.' MINUTE)');
    }
    
}
?>