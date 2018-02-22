<?php
/**
 * 
 * Represents the Settings
 * 
 * @author Cari
 *
 */

class Setting {
    
    protected $name;
    protected $value;
    protected $description;
    protected $db;
   
    public function __construct($name, $value, $description = '') {
        $this->name         = $name;
        $this->value        = $value;
        $this->description  = $description;
        $this->db           = Db::getInstance();
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
    
    public function store() {
        // TODO: catch exception!
        try{
            $req = $this->db->prepare('UPDATE settings SET value = :value WHERE name = :name');
            $req->execute(array(':name'=>$this->name,':value'=>$this->value));
            return true;
        }
        catch (PDOException $e){
            return "Fehler beim Schreiben in die Datenbank. Datanbank meldet: ".$e->getMessage();
        }
    }
    
    public static function getALL(){
        $settings = array();
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM settings ORDER BY name ASC');
        $req->execute();
        
        if($req->rowCount() > 0){
            foreach($req->fetchALL(PDO::FETCH_OBJ) as $setting){
                $settings[] = new Setting($setting->name, $setting->value, $setting->description);
            }
        }

        return $settings;
    }
    

}