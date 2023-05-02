<?php
/**
 * 
 * Represents a Game Setting
 * 
 * @author NekoCari
 *
 */

class GameSetting extends DbRecordModel {
    
    protected $id, $game_key, $type, $wait_time, $route_identifier, $name_json, $description_json, $preview_img_path, $game_img_path;
    
    protected static 
        $db_table = 'games_settings',
        $db_pk = 'id',
        $db_fields = array('id','game_key','type','wait_time','route_identifier','name_json','description_json','preview_img_path','game_img_path'),
        $sql_order_by_allowed_values = array('id','game_key');
    
    private static $allowed_types = array('custom','lucky');
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * get a game setting by id
     * @param int $id
     * @return GameSetting|NULL
     */
    public static function getById($id) {
    	return parent::getByPk($id);
    }
    
    /**
     * get a game setting by game key
     * @param string $key
     * @return GameSetting|NULL
     */
    public static function getByKey($key) {
    	return parent::getByUniqueKey('game_key',$key);
    }
    
        
    /**
     * returns true if game is only playable once per day
     * @return boolean
     */
    public function isDailyGame(){
        if($this->getWaitTime() === false){
            return true;
        }else{
            return false;
        }
    }
    
    
    /*
     * Getter
     */
    public function getId() {
    	return $this->id;
    }
    public function getKey() {
    	return $this->game_key;
    }
    public function getType() {
    	return $this->type;
    }
    public function getWaitTime() {
    	if(is_numeric($this->wait_time)){
			return $this->wait_time;
    	}else{
    		return false;
    	}
    }
    public function getRouteIdentifier() {
    	return $this->route_identifier;
    }
    public function getName($lang='de') {
    	$name_arr = json_decode($this->name_json,true);
    	if(!isset($name_arr[$lang])){
    		return $name_arr[0];
    	}
    	return $name_arr[$lang];
    }
    public function getDescription($lang='de') {
    	$description_arr = json_decode($this->description_json,true);
    	if(!isset($description_arr[$lang])){
    		return $description_arr[0];
    	}
    	return $description_arr[$lang];
    }
    
    public static function getAllowedTypes(){
        return self::$allowed_types;
    }
    
    // SETTER
    
    /**
     * @param string $game_key
     */
    public function setKey($game_key) {
    	$this->game_key = $game_key;
    }
    
    public function setRouteIdentifier($identifier) {
    	// TODO: check if identifier is valid
    	$this->route_identifier = $identifier;
    }
    
    public function setType($type) {
    	if(in_array($type, $this->getAllowedTypes())){
    		$this->type = $type;
    	}else{
    		throw new ErrorException('invalid type');
    	}
    }
    
    public function setName($arr){
    	if(is_array($arr)){
    		$this->name_json = json_encode($arr);
    	}else{
    		throw new ErrorException('parameter needs to be array');
    	}
    }
    
    public function setDescription($arr){
    	if(is_array($arr)){
    		$this->description_json = json_encode($arr);
    	}else{
    		throw new ErrorException('parameter needs to be array');
    	}
    }
    
    public function setWaitTime($min) {
    	if(!is_null($min)){
    		$this->wait_time = max(0,intval($min));
    	}else{
    		$this->wait_time = null;
    	}
    }
    
    public function update(){
    	$this->validate();
    	parent::update();
    }
    
    private function validate() {
    	// TODO preg match game_key
    	if($this->wait_time < 0 AND !is_null($this->wait_time)){
    		$this->wait_time = NULL;
    	}
    	if(!in_array($this->getType(), $this->getAllowedTypes())){
    		throw new ErrorException($this->getType().' is not an allowed game type!');
    	}
    }
    
    
    
}