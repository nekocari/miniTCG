<?php
/**
 * 
 * Represents a Update Member relation
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/update.php';

class UpdateMember extends DbRecordModel {
    
    protected $member_id, $update_id;
    private $member_obj, $update_obj;
    
    protected static
        $db_table = 'updates_members',
        $db_pk = 'member_id,update_id',
        $db_fields = array('member_id','update_id'),
        $sql_order_by_allowed_values = array('update_id','member_id');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public static function getRelationsByUpdateId($id) {
        return parent::getWhere('update_id = '.intval($id));
    }
    
    public static function getRelationsByMemberId($id) {
        return parent::getWhere('memberid = '.intval($id));
    }
    
    public function getUpdate(){
        if(!$this->update_obj instanceof Update){
            $this->update_obj = Update::getById($this->update_id);
        }
        return $this->update_obj;
    }
    
    public function getMember(){
        if(!$this->member_obj instanceof Member){
            $this->member_obj = Member::getById($this->member_id);
        }
        return $this->member_obj;
    }
    
}