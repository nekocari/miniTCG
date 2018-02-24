<?php
/**
 * 
 * Represents a Carddeck
 * 
 * @author Cari
 *
 */

class Carddeck {
    
    private $id;
    private $name;
    private $deckname;
    private $status;
    private $creator_id;
    private $creator_name;
    private $date;
    private $db;
    public static $naming_pattern = "/[A-Za-z0-9äÄöÖüÜß _\-]+/";
    
    public function __construct($id, $name, $deckname, $status, $date, $creator_id, $creator_name) {
        $this->id           = $id;
        $this->name         = $name;
        $this->deckname     = $deckname;
        $this->status       = $status;
        $this->creator_id   = $creator_id;
        $this->creator_name = $creator_name;
        $this->date         = $date;
        $this->db           = DB::getInstance();
    }
    
    public static function getAll() {
        $decks = array();
        $db = DB::getInstance();
        
        $req = $db->query('SELECT d.*, m.name as creator_name FROM decks d JOIN members m ON m.id = d.creator ORDER BY d.id DESC');
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name);
            }
        }
        return $decks;
    }
    
    public static function getById($id) {
        $deck = false;
        $db = DB::getInstance();
        
        $req = $db->prepare('SELECT d.*, m.name as creator_name FROM decks d JOIN members m ON m.id = d.creator WHERE d.id = :id');
        $req->execute(array(':id'=>$id));
        if($req->rowCount() > 0){
            $deck = $req->fetch(PDO::FETCH_OBJ);
        }
        return new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name);
    }
    
    public static function update($id, $name, $creator) {
        $db = DB::getInstance();
        if(preg_match(self::$naming_pattern, $name)){
            $req = $db->prepare('UPDATE decks SET name = :name, creator = :creator WHERE id = :id');
            return $req->execute(array(':id'=>$id, ':name'=>$name, ':creator'=>$creator));
        }else{
            return 'Der Name enthält mindestens ein ungültiges Zeichen.';
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDeckname() {
        return $this->deckname;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getCreator() {
        return $this->creator_id;
    }
    
    public function getCreatorName() {
        return $this->creator_name;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getImageUrls() {
        $urls = array();
        require_once PATH.'models/setting.php';
        $setting_file_type = Setting::getByName('cards_file_type')->getValue();
        $setting_cards_decksize = Setting::getByName('cards_decksize')->getValue();
        $deckname = $this->getDeckname();
        for($i = 1; $i <= $setting_cards_decksize; $i++){-
            $urls[$i] = CARDS_FOLDER.$deckname.'/'.$deckname.$i.'.'.$setting_file_type;
        }
        $urls['master'] = CARDS_FOLDER.$deckname.'/'.$deckname.'_master.'.$setting_file_type;
        return $urls;
    }
    
    public function getImages() {
        $card_images = array();
        require_once PATH.'models/setting.php';
        $setting_tpl_width = Setting::getByName('cards_template_width')->getValue();
        $setting_tpl_height = Setting::getByName('cards_template_height')->getValue();
        $setting_master_tpl_width = Setting::getByName('cards_master_template_width')->getValue();
        $setting_master_tpl_height = Setting::getByName('cards_master_template_height')->getValue();
        $cardimage_tpl = file_get_contents(PATH.'views/templates/card_image_temp.php');
        
        $image_urls = $this->getImageUrls();
        
        $tpl_placeholder = array('[WIDTH]','[HEIGHT]','[URL]');
        
        foreach($image_urls as $key=>$url){
            if($key != 'master'){
                $replace = array($setting_tpl_width, $setting_tpl_height, $url);
            }else{
                $replace = array($setting_master_tpl_width, $setting_master_tpl_height, $url);
            }
            $card_images[$key] = str_replace($tpl_placeholder, $replace, $cardimage_tpl);
        }
        return $card_images;
    }
}