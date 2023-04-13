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
    
    protected static
    $db_table = 'settings',
    $db_pk = 'name',
    $db_fields = array('name','value','description'),
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
    
    public function getDescription() {
        return $this->description;
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
        return parent::getByPk($name);
    }
    

}