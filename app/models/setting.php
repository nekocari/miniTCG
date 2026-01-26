<?php
/**
 * 
 * Represents the Settings
 * 
 * @author NekoCari
 *
 */


class Setting extends DbRecordModel {
    
    protected $name, $value, $description;
    
    private static $loaded_settings = array();
    
    protected static
    $db_table = 'settings',
    $db_pk = 'name',
    $db_fields = array('name','value','description','meta'),
    $sql_order_by_allowed_values = array('name');
   
    public function __construct() {
        parent::__construct();
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function getDescription($lang='de') {
    	$description = json_decode($this->description,true);
    	if(isset($description[$lang])){
    		return $description[$lang];
    	}else{
    		return $description[0];
    	}
    }
    
    /**
     * 
     * @param string $field
     * @return mixed
     */
    public function getMeta($field) {
    	$metadata = json_decode($this->meta,true);
    	if(isset($metadata[$field])){
    		return $metadata[$field];
    	}else{
    		return null;
    	}
    }
    
    public function setValue($value) {
        $this->value = $value;
    }    
    
    /**
     * Returns a single setting entry
     * @param string $name
     * @return Setting|NULL
     */
    public static function getByName($name){
    	if(!isset(self::$loaded_settings[$name])){
	        $setting = parent::getByPk($name);
	        if(!$setting instanceof Setting){
	        	$setting = new Setting();
	        	$setting->setPropValues(['name'=>$name]);
	        }
    	}else{
    		$setting = self::$loaded_settings[$name];
    	}
        return $setting;
    }
    

}