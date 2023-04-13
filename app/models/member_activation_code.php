<?php
/**
 * 
 * Represents a Member Activation Code
 * 
 * @author Cari
 *
 */

class MemberActivationCode extends DbRecordModel{
    
    protected $member_id, $code;
    private $member_obj;
    
    protected static
        $db_table = 'members_activation_code',
        $db_pk = 'member_id',
        $db_fields = array('member_id','code'),
        $sql_order_by_allowed_values = array('member_id','code');
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getByMemberId($id) {
        return parent::getByUniqueKey('member_id',$id);
    }
    
    public static function getByCodeId($code) {
        return parent::getByUniqueKey('code',$code);
    }
    
    public function getMember(){
        return Member::getById($this->member_id);
    }
    
    public function getCode(){
        return $this->code;
    }
        
}