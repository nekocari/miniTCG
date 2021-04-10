<?php
/**
 * 
 * Represents the Subcategories
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/category.php';

class Subcategory extends Category {
    
    protected $category;
    private $decks;
    
    protected static
    $db_table = 'subcategories',
    $db_pk = 'id',
    $db_fields = array('id','name','category'),
    $sql_order_by_allowed_values = array('id','name','category');
   
    public function __construct() {
        parent::__construct();
    }
    
    public function getCategory() {
        return $this->category;
    }
    public function getCategoryObj() {
        return Category::getById($this->category);
    }
    
    public function setCategory($category) {
        $this->category = $category;
    }
    
    public static function getByCategory($category){
        return parent::getWhere('category = '.intval($category));
    }
    
    public static function getById($id){
        return parent::getByPk($id);
    }
    
    public function getDecks($status='public',$order_settings=['deckname'=>'ASC']){
        if(is_null($this->decks)){
            $this->decks = Carddeck::getBySubcategory($this->getId(),$status,$order_settings);
        }
        return $this->decks;
    }
    
   
    public static function add($name) {
        list($name, $category) = func_get_args();
        try {
            if(preg_match('/[A-Za-z0-9äÄöÖüÜß _\-]+/', $name)){
                $subcategory = new Subcategory();
                $subcategory->setPropValues(['name'=>$name, 'category'=>$category]);
                if($subcategory->create()){
                    return true;
                }else{
                    return false;
                }
            }else{
                throw new Exception('Eingabe enthält ein ungültiges Zeichen.');
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
}