<?php
/**
 * 
 * Represents a Right
 * 
 * @author Cari
 *
 */

class Right {
    
    private $id, $name, $description, $db;
    
    public function __construct($id, $name, $description) {
        $this->id           = $id;
        $this->name         = $name;
        $this->description  = $description;
        $this->db           = DB::getInstance();
    }
    
    public static function getAll() {
        
        try {
            $db = Db::getInstance();
            $rights = array();
           
            $req = $db->query('SELECT * FROM rights ORDER BY name ASC');
            
            if($req->rowCount() > 0){
                
                foreach($req->fetchAll(PDO::FETCH_OBJ) as $right){  
                    
                    $rights[] = new Right($right->id, $right->name, $right->description);
                    
                }
            }
            
            return $rights;
        }
        
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
    }
    
    
}