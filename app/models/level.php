<?php
/**
 * 
 * Represents a Level
 * 
 * @author NekoCari
 *
 */

class Level extends DbRecordModel {
    
    protected $id, $level, $name, $cards;
    
    protected static
        $db_table = 'level',
        $db_pk = 'id',
        $db_fields = array('id','name','level','cards'),
        $sql_order_by_allowed_values = array('id','name','level','cards');
    
    private static $naming_pattern = '/[A-Za-z0-9äÄöÖüÜß _\-]+/';
    
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * get data of all levels from database
     * 
     * @return Level[]
     */
    public static function getAll($order_settings = ['level'=>'ASC'], $limit = null) {
        return parent::getAll($order_settings, $limit);
        
    }
    
    /**
     * get level data from database using id number
     * 
     * @param int $id Id number of level in database
     * 
     * @return Level|NULL 
     */
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    /**
     * creates a new Level in db and returns the object
     * @param int $level
     * @param string $name
     * @param int $cards
     * @throws Exception
     * @return Level
     */
    public static function add($level, $name, $cards) {
        
            if(preg_match(self::$naming_pattern, $name) AND intval($cards) >= 0){
                $newlevel = new Level();
                $newlevel->setPropValues(['level'=>$level,'name'=>$name,'cards'=>$cards]);
                $level_id = $newlevel->create();
                $newlevel->setPropValues(['id'=>$level_id]);
                return $newlevel;
            }else{
                throw new Exception('Name enthält mindestens ein ungültiges Zeichen.',1001);
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
    
    /**
     * get level matching a number of cards
     * 
     * @param int $number
     * @return Level|NULL
     */
    public static function getByCardNumber($number) {
        $level_array = parent::getWhere('cards < '.intval($number),['level'=>'DESC']);
        if(count($level_array) > 0){
            return $level_array[0];
        }else{
            return null;
        }
        /*
        $db = DB::getInstance();
        $req = $db->prepare('SELECT * FROM level WHERE cards < :number ORDER BY level DESC LIMIT 1');
        $req->execute(array(':number'=>$number));
        if($req->rowCount()){
            $data = $req->fetch();
            return new Level($data['id'], $data['level'], $data['name'], $data['cards']);
        }else{
            return false;
        }
        */
    }
    
    /**
     * Get next level after current
     * 
     * @return Level|NULL
     */
    public function next() {
        $level_array = parent::getWhere('level > '.$this->getLevel(),['level'=>'ASC']);
        if(count($level_array) > 0){
            return $level_array[0];
        }else{
            return null;
        }
        /*
        $req = $this->db->prepare('SELECT * FROM level WHERE level > :level ORDER BY level ASC LIMIT 1');
        $req->execute(array(':level'=>$this->getLevel()));
        if($req->rowCount()){
            $data = $req->fetch();
            return new Level($data['id'], $data['level'], $data['name'], $data['cards']);
        }else{
            return false;
        }*/
    }
    
}