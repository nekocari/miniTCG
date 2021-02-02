<?php
/**
 * 
 * Represents a Member Right
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/member.php';
require_once PATH.'models/right.php';

class MemberRight extends DbRecordModel {
    
    protected $member_id, $right_id;
    private $member_obj, $right_obj;
    
    protected static
        $db_table = 'members_rights',
        $db_pk = array('member_id','right_id'),
        $db_fields = array('member_id','right_id'),
        $sql_order_by_allowed_values = array();
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * gets all rights for assigend to member
     * @param int $member_id
     * @return Right[]
     */
    public static function getByMemberId($member_id){
        return parent::getWhere('member_id = '.intval($member_id));
    }
    
    /**
     * gets all members with right assigned
     * @param int $right_id
     * @return Right[]
     */
    public static function getByRightId($right_id){
        return parent::getWhere('right_id = '.intval($right_id));
    }
    
    public function getRight() {
        if(!$this->right_obj instanceof Right){
            $this->right_obj = Right::getByPk($this->right_id);
        }
        return $this->right_obj;
    }
    public function getMember() {
        if(!$this->member_obj instanceof Member){
            $this->member_obj = Member::getById($this->member_id);
        }
        return $this->member_obj;
    }
    
    public function delete(){
        if(isset(self::$db_table)){
            $req = $this->db->prepare('DELETE FROM '.self::$db_table.' WHERE member_id = ? AND right_id = ?');
            $req->execute([$this->member_id, $this->right_id]);
            if($req->rowCount() == 1){
                return true;
            }else{
                return false;
            }
        }else{
            throw new ErrorException('db table is not set');
        }
    }
    
    
}