<?php
/**
 * 
 * Represents a UserSetting
 * 
 * @author NekoCari
 *
 */

class MemberSetting extends DbRecordModel {
    
	protected $member_id, $language, $timezone;
    
    
    // for active record
    protected static
    $db_table = 'members_settings',
    $db_pk = 'member_id',
    $db_fields = array('member_id','language','timezone'),
	$sql_order_by_allowed_values = array('member_id'),
 	$sql_direction_allowed_values = array('DESC','ASC');
   
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 
     * @param int $member_id
     * @return MemberSetting|NULL
     */
    public static function getByMemberId($member_id) {
        return parent::getByPk($member_id);
    }
    
    /**
     * 
     * @return int
     */
    public function getMemberId() {
        return $this->member_id;
    }   
    
    /**
     * 
     * @param string $name name of the setting var
     * @return mixed|NULL
     */
    public function getValueByName($name) {
        if(isset($this->{$name})){
            return $this->{$name};
        }else{
            return null;
        }
    }
    
    
    public function setPropValues($array) {
    	parent::setPropValues($array);
    }
    
    
    

}