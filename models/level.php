<?php
/**
 * 
 * Represents a Level
 * 
 * @author Cari
 *
 */
class Level {
    
    private $id;
    private $level;
    private $name;
    private $cards;
    private static $naming_pattern = '/[A-Za-z0-9äÄöÖüÜß _\-]+/';
    private $db;
    
    
    public function __construct($id, $level, $name, $cards) {
        $this->id = $id;
        $this->level = $level;
        $this->name = $name;
        $this->cards = $cards;
        $this->db = Db::getInstance();
    }
    
    /**
     * get data of all levels from database
     * 
     * @param Db $db_conn PDO connection object
     * 
     * @return boolean|Level[]
     */
    public static function getAll() {
        $level = array();
        $db_conn = Db::getInstance();
    
        $req = $db_conn->query("SELECT * FROM level ORDER BY level ASC");
        if($req->execute()){
            foreach($req->fetchAll() as $data) {
                $level[$data['level']] = new Level($data['id'], $data['level'], $data['name'], $data['cards']);
            }
        }else{
            return false;
        }
        
        return $level;
    }
    
    /**
     * get level data from database using id number
     * 
     * @param int $id Id number of level in database
     * @param Db $db_conn PDO connection object
     * 
     * @return boolean|Level 
     */
    public static function getById($id) {
        $level = false;
        $db_conn = Db::getInstance();
        
        $req = $db_conn->prepare('SELECT * FROM level WHERE id = :id');
        if($req->execute(array(':id' => $id))) {
            if($req->rowCount()) {
                $data = $req->fetch();
                $level = new Level($data['id'], $data['level'], $data['name'], $data['cards']);
            }
        }else{
            return false;
        }
        
        return $level;
    }
    
    public static function add($level, $name, $cards) {
        $db_conn = Db::getInstance();
        try {
            if(preg_match(self::$naming_pattern, $name) AND intval($cards) >= 0){
                $req = $db_conn->prepare('INSERT INTO level (level, name, cards) VALUES (:level, :name, :cards)');
                $req->execute(array(':level'=>$level, ':name'=>$name, 'cards'=>$cards));
                return true;
            }else{
                throw new Exception('Name enthält mindestens ein ungültiges Zeichen.');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function update($level, $name, $cards) {
        try {
            if(preg_match(self::$naming_pattern, $name) AND intval($cards) >= 0 AND intval($level) >= 0){
                $req = $this->db->prepare('UPDATE level SET level = :level, name = :name, cards = :cards WHERE id = :id');
                $req->execute(array(':level'=>$level, ':name'=>$name, ':cards'=>$cards, ':id'=>$this->id));
                $this->name = $name;
                $this->cards = $cards;
                $this->level = $level;
                return true;
            }else{
                throw new Exception('Name enthält mindestens ein ungültiges Zeichen.');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /*
     * Getter
     */
    public function getCards() {
        return $this->cards;
    }
    public function getLevel() {
        return $this->level;
    }
    public function getName() {
        return $this->name;
    }
    public function getId() {
        return $this->id;
    }
    
}