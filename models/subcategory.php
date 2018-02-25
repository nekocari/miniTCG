<?php
/**
 * 
 * Represents the Subcategories
 * 
 * @author Cari
 *
 */
require_once 'category.php';

class Subcategory extends Category {
    
    protected $category_id;
   
    public function __construct($id, $name, $category_id) {
        $this->id           = $id;
        $this->name         = $name;
        $this->category_id  = $category_id;
        $this->db           = Db::getInstance();
    }
    
    public function getCategory() {
        return $this->category_id;
    }
    
    public function setCategory($category) {
        $this->category_id = $category;
    }
    
    public function store() {
        // TODO: catch exception!
        $req = $this->db->prepare('UPDATE subcategories SET name = :name, category = :category WHERE id = :id');
        $req->execute(array(':name'=>$this->name, ':category'=>$this->category_id, ':id'=>$this->id));
        
        if($req->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function getALL(){
        $subcategories = array();
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM subcategories ORDER BY name ASC');
        $req->execute();
        
        if($req->rowCount() > 0){
            foreach($req->fetchALL(PDO::FETCH_OBJ) as $subcategory){
                $subcategories[$subcategory->id] = new Subcategory($subcategory->id, $subcategory->name, $subcategory->category);
            }
        }
        return $subcategories;
    }
    
    public static function getByCategory($category){
        $subcategories = array();
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM subcategories WHERE category = :category ORDER BY name ASC');
        $req->execute(array(':category'=>$category));
        
        if($req->rowCount() > 0){
            foreach($req->fetchALL(PDO::FETCH_OBJ) as $subcategory){
                $subcategories[$subcategory->id] = new Subcategory($subcategory->id, $subcategory->name, $subcategory->category);
            }
        }
        return $subcategories;
    }
    
    public static function getById($id){
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM subcategories WHERE id = :id');
        $req->execute(array(':id'=>$id));
        
        if($req->rowCount() > 0){
            $subcategory = $req->fetch(PDO::FETCH_OBJ);
            return new Subcategory($subcategory->id, $subcategory->name, $subcategory->category);
        }else{
            return false;
        }
    }
    
    public static function add($name) {
        list($name, $category) = func_get_args();
        try {
            if(preg_match('/[A-Za-z0-9äÄöÖüÜß _\-]+/', $name)){
                $db = Db::getInstance();
                $req = $db->prepare('INSERT INTO subcategories (name, category) VALUES (:name, :category)');
                try{
                    $req->execute(array(':name'=>$name, ':category'=>$category));
                    return true;
                }
                catch (PDOException $pdo_e){
                    return 'Einfügen in DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
                }
            }else{
                throw new Exception('Eingabe enthält ein ungültiges Zeichen.');
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function delete($id) {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM subcategories WHERE id = :id');
        try{
            $req->execute(array(':id'=>$id));
            return true;
        }
        catch (PDOException $pdo_e){
            return 'Löschen aus der DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
        }
    }
}