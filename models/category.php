<?php
/**
 * 
 * Represents the Categories
 * 
 * @author Cari
 *
 */

class Category {
    
    protected $id;
    protected $name;
    protected $db;
   
    public function __construct($id, $name) {
        $this->id   = $id;
        $this->name = $name;
        $this->db   = Db::getInstance();
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
        return 'decks/category.php?id='.$this->id;
    }
    
    public function store() {
        // TODO: catch exception!
        $req = $this->db->prepare('UPDATE categories SET name = :name WHERE id = :id');
        $req->execute(array(':name'=>$this->name,':id'=>$this->id));
        
        if($req->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function getALL(){
        $categories = array();
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM categories ORDER BY name ASC');
        $req->execute();
        
        if($req->rowCount() > 0){
            foreach($req->fetchALL(PDO::FETCH_OBJ) as $category){
                $categories[$category->id] = new Category($category->id, $category->name);
            }
        }

        return $categories;
    }
    
    public static function getById($id) {
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM categories WHERE id = :id');
        $req->execute(array(':id'=>$id));
        
        if($req->rowCount() > 0){
            $category = $req->fetch(PDO::FETCH_OBJ);
            return new Category($category->id, $category->name);
        }else{
            return false;
        }
    }
    
    public static function add($name) {
        try {
            if(preg_match('/[A-Za-z0-9äÄöÖüÜß _\-]+/', $name)){
                $db = Db::getInstance();
                $req = $db->prepare('INSERT INTO categories (name) VALUES (:name)');
                try{
                    $req->execute(array(':name'=>$name));
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
        $req = $db->prepare('DELETE FROM categories WHERE id = :id');
        try{
            $req->execute(array(':id'=>$id));
            return true;
        }
        catch (PDOException $pdo_e){
            return 'Löschen aus der DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
        }
    }
}