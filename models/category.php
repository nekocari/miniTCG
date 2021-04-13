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
    
    /**
     * get categories containing decks 
     * @param array $order_settings
     * @return Category[]
     */
    public static function getNotEmpty($deck_status='public',$order_settings=null) {
        if(!in_array($deck_status, Carddeck::getAcceptedStati())){
            $deck_status == Carddeck::getAcceptedStati()[0];
        }
        $db = Db::getInstance();
        $categories = array();
        $sql = 'SELECT DISTINCT c.* 
                FROM subcategories sc
                JOIN categories c ON sc.category = c.id
                JOIN decks_subcategories dsc ON dsc.subcategory_id = sc.id
                JOIN decks d ON d.id = dsc.deck_id AND d.status = \''.$deck_status.'\' ';
        if(is_array($order_settings)){
            $sql.= parent::buildSqlPart('order_by',$order_setting);
        }
        $req = $db->query($sql);
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $category){
                $categories[] = $category;
            }
        }
        return $categories;
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