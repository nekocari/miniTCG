<?php
/**
 * 
 * Represents a Right
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';

class Right extends DbRecordModel {
    
    protected $id, $name, $description;
    
    protected static
        $db_table = 'rights',
        $db_pk = 'id',
        $db_fields = array('id','name','description'),
        $sql_order_by_allowed_values = array('id','name');
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * gets als rights for assigend to member
     * @param int $member_id
     * @return Right[]
     */
    public static function getByMemberId($member_id){
        // TODO
        return [];
    }
    
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
    }
    
    
}