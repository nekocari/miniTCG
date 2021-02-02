<?php
/**
 * 
 * Represents the Categories
 * 
 * @author Cari
 *
 */

require_once PATH.'models/db_record_model.php';
require_once PATH.'models/subcategory.php';

class Category extends DbRecordModel {
    
    protected $id, $name;
    
    protected static
    $db_table = 'categories',
    $db_pk = 'id',
    $db_fields = array('id','name'),
    $sql_order_by_allowed_values = array('id','name');
   
    public function __construct() {
        parent::__construct();
    }
    
    public function setName($name) {
        if(preg_match('/[A-Za-z0-9äÄöÖüÜß _\-]+/', $name)){
            $this->name = $name;
            return true;
        }else{
            return false;
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getLinkUrl() {
        return Routes::getUri('deck_by_category').'?id='.$this->id;
    }
    
    public function store() {
        return parent::update();
    }
    
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    public function getSubcategories(){
        return Subcategory::getByCategory($this->getId());
    }
    
    public static function add($name) {
        
        if(preg_match('/[A-Za-z0-9äÄöÖüÜß _\-]+/', $name)){
            $category = new Category();
            $category->setPropValues(['name'=>$name]);
            if($category->create()){
                return true;
            }else{
                return false;
            }
           
        }else{
            throw new Exception('Eingabe enthält ein ungültiges Zeichen.');
        }
        
    }
}