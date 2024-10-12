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
    
    /**
     * retuns all Elements from Database
     * @param array $order_settings [fieldname=>direction]
     * @param int $limit optional limit settings
     * @return CardStatus[]
     */
    public static function getAll($order_settings=['position'=>'ASC'],$limit=null){
    	return parent::getAll($order_settings, $limit);
    }
    
    /**
     * retuns the status ment for new cards
     * @return CardStatus
     */
    public static function getNew() {
    	$new = parent::getWhere(['new'=>1]);
    	if(is_array($new) AND count($new) >= 1){
    		return $new[0];
    	}else{
    		throw new ErrorException('no status selected for new cards!');
    	}
    }
    
    /**
     * retuns the status ment for collections
     * @return CardStatus
     */
    public static function getCollect() {
    	$collect = parent::getWhere(['collections'=>1]);
    	if(is_array($collect) AND count($collect) >= 1){
    		return $collect[0];
    	}else{
    		throw new ErrorException('no status selected for collection cards!');
    	}
    }
    
    
    /**
     * retuns all status for untradeable cards besides new and collect
     * @return CardStatus[]
     */
    public static function getNotTradeable($order_settings = ['position'=>'ASC']) {
    	return parent::getWhere(['tradeable'=>0,'collections'=>0,'new'=>0]);
    }
    
    
    /**
     * retuns all status for tradeable cards
     * @return CardStatus[]
     */
    public static function getTradeable() {
    	return parent::getWhere(['tradeable'=>1]);
    }
    
    
    // Getter
    
    public function getId() {
    	return $this->id;
    }
    
    public function getName() {
    	return $this->name;
    }
    
    public function getPosition() {
    	return $this->position;
    }
    
    public function isTradeable() {
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
    
    
    // Setter
    
    public function setName($value){
    	$this->name = $value;
    	return true;
    }
    
    public function setPosition($value){
    	if(is_int($value)){
    		$this->position = $value;
    		return true;
    	}
    	return false;
    }
    
    public function setTradeable($value){
    	if(is_int($value) AND ($value == 1 OR $value ==0)){
    		$this->tradeable = $value;
    		return true;
    	}
    	return false;
    }
    
    public function setCollections($value){
    	if(is_int($value) AND ($value == 1 OR $value ==0)){
    		$this->collections = $value;
    		return true;
    	}
    	return false;
    }
    
    public function setNew($value){
    	if(is_int($value) AND ($value == 1 OR $value ==0)){
    		$this->new = $value;
    		return true;
    	}
    	return false;
    }
    
    public function setPublic($value){
    	if(is_int($value) AND ($value == 1 OR $value ==0)){
    		$this->public = $value;
    		return true;
    	}
    	return false;
    }
    
    public function update() {
    	// make sure these values will be stored as 0 and 1 in database
    	$this->collections = ''.intval(boolval($this->collections));
    	$this->tradeable = ''.intval(boolval($this->tradeable));
    	$this->new = ''.intval(boolval($this->new));
    	$this->public = ''.intval(boolval($this->public));
    	return parent::update();
    }
     /**
      * 
      * {@inheritDoc}
      * @see DbRecordModel::delete()
      */
    public function delete() {
    	if($this->isCollections()){
    		throw new ErrorException('status_is_collect');
    	}elseif($this->isNew()){
    		throw new ErrorException('status_is_new');
    	}else{
    		return parent::delete();
    	}
    }
    
}