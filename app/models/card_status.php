<?php
/**
 * 
 * Represents a CardStatus
 * 
 * @author NekoCari
 *
 */

class CardStatus extends DbRecordModel {
    
    protected $id, $name, $position, $tradeable, $collections, $new, $public;
    
    
    protected static 
        $db_table = 'cards_stati',
        $db_pk = 'id',
        $db_fields = array('id','name','position','tradeable','collections','new','public'),
        $sql_order_by_allowed_values = array('id','name','position');
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getById($id) {
    	return parent::getByPk($id);
    }
    
    public static function getByName($name) {
    	return parent::getByUniqueKey($name);
    }
    
    public function getId() {
    	return $this->id;
    }
    
    public function getName() {
    	return $this->name;
    }
    
    public function getPosition() {
    	return $this->position;
    }
    
    public function isTradable() {
    	return boolval($this->tradeable);
    }
    
    public function isCollections() {
    	return boolval($this->collections);
    }
    
    public function isNew() {
    	return boolval($this->new);
    }
    
    public function isPublic() {
    	return boolval($this->public);
    }
    
    public function update() {
    	// make sure these values will be stored as 0 and 1 in database
    	$this->collections = intval(boolval($this->collections));
    	$this->tradeable = intval(boolval($this->tradeable));
    	$this->new = intval(boolval($this->new));
    	$this->public = intval(boolval($this->public));
    	return parent::update();
    }
    
}