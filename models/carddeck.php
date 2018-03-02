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
    private $category;
    private $category_name;
    private $subcategory;
    private $subcategory_name;
    private $db;
    private static $naming_pattern = "/[A-Za-z0-9äÄöÖüÜß _\-]+/";
    private static $allowed_status = array('new','public'); 
    private static $date_formate = DATE_FORMATE;
    
    public function __construct($id, $name, $deckname, $status, $date, $creator_id, $creator_name, $category, $subcategory, $category_name, $subcategory_name) {
        $this->id           = $id;
        $this->name         = $name;
        $this->deckname     = $deckname;
        $this->status       = $status;
        $this->creator_id   = $creator_id;
        $this->creator_name = $creator_name;
        $this->date         = $date;
        $this->category     = $category;
        $this->subcategory  = $subcategory;
        $this->category_name     = $category_name;
        $this->subcategory_name  = $subcategory_name;
        $this->db           = DB::getInstance();
    }
    
    public static function getAll() {
        $decks = array();
        $db = DB::getInstance();
        
        $req = $db->query('SELECT d.*, m.name as creator_name , 
                        s.id as sub_id, s.name as sub_name, 
                        c.id as cat_id, c.name as cat_name
                    FROM decks d
                    JOIN members m ON m.id = d.creator
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                    JOIN subcategories s ON s.id = ds.subcategory_id
                    JOIN categories c ON s.category = c.id
                    ORDER BY d.id DESC');
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name, 
                                        $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
            }
        }
        return $decks;
    }
    
    public static function getAllByStatus($status = 'public') {
        $decks = array();
        $db = DB::getInstance();
        
        if(!in_array($status,self::$allowed_status)){
            throw new Exception('Status enthält unerlaubten Wert.');
        }
        
        $req = $db->prepare('SELECT d.*, m.name as creator_name , 
                        s.id as sub_id, s.name as sub_name, 
                        c.id as cat_id, c.name as cat_name
                    FROM decks d
                    JOIN members m ON m.id = d.creator
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                    JOIN subcategories s ON s.id = ds.subcategory_id
                    JOIN categories c ON s.category = c.id
                    WHERE d.status = :status
                    ORDER BY d.id DESC');
        $req->execute(array(':status'=>$status));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name,
                                        $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
            }
        }
        return $decks;
    }
    
    public static function getById($id) {
        $deck = false;
        $db = DB::getInstance();
        
        $query = 'SELECT d.*, m.name as creator_name , 
                        s.id as sub_id, s.name as sub_name, 
                        c.id as cat_id, c.name as cat_name
                    FROM decks d
                    JOIN members m ON m.id = d.creator
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                    JOIN subcategories s ON s.id = ds.subcategory_id
                    JOIN categories c ON s.category = c.id
                    WHERE d.id = :id';
        $req = $db->prepare($query);
        $req->execute(array(':id'=>$id));
        if($req->rowCount() > 0){
            $deck = $req->fetch(PDO::FETCH_OBJ);
            return new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name,
            $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
        }else{
            return false;
        }
        
    }
    
    public static function getBySubcategory($sub_id) {
        $decks = array();
        $db = DB::getInstance();
        
        $query = 'SELECT d.*, m.name as creator_name ,
                        s.id as sub_id, s.name as sub_name,
                        c.id as cat_id, c.name as cat_name
                    FROM decks d
                    JOIN members m ON m.id = d.creator
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                    JOIN subcategories s ON s.id = ds.subcategory_id
                    JOIN categories c ON s.category = c.id
                WHERE ds.subcategory_id = :sub ORDER BY d.id ASC';
        $req = $db->prepare($query);
        $req->execute(array(':sub'=>$sub_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name,
                    $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
            }
        }
        return $decks;
    }
    
    public static function getNotInUpdate() {
        $decks = array();
        $db = DB::getInstance();
        
        $query = 'SELECT d.*, m.name as creator_name ,
                        s.id as sub_id, s.name as sub_name,
                        c.id as cat_id, c.name as cat_name
                    FROM decks d
                    JOIN members m ON m.id = d.creator
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                    JOIN subcategories s ON s.id = ds.subcategory_id
                    JOIN categories c ON s.category = c.id
                    LEFT JOIN updates_decks ud ON ud.deck_id = d.id
                WHERE ud.update_id IS NULL ORDER BY d.id ASC';
        $req = $db->prepare($query);
        $req->execute();
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name,
                    $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
            }
        }
        return $decks;
    }
    
    public static function getInUpdate($update_id) {
        $decks = array();
        $db = DB::getInstance();
        
        $query = 'SELECT d.*, m.name as creator_name ,
                        s.id as sub_id, s.name as sub_name,
                        c.id as cat_id, c.name as cat_name
                    FROM decks d
                    JOIN members m ON m.id = d.creator
                    JOIN decks_subcategories ds ON ds.deck_id = d.id
                    JOIN subcategories s ON s.id = ds.subcategory_id
                    JOIN categories c ON s.category = c.id
                    LEFT JOIN updates_decks ud ON ud.deck_id = d.id
                WHERE ud.update_id = :update_id ORDER BY d.id ASC';
        $req = $db->prepare($query);
        $req->execute(array(':update_id'=>$update_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->date, $deck->creator, $deck->creator_name,
                    $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
            }
        }
        return $decks;
    }
    
    public static function update($id, $name, $creator, $subcat) {
        $db = DB::getInstance();
        try{
            if(preg_match(self::$naming_pattern, $name)){
                $req = $db->prepare('UPDATE decks SET name = :name, creator = :creator WHERE id = :id');
                $req->execute(array(':id'=>$id, ':name'=>$name, ':creator'=>$creator));
                $req = $db->prepare('UPDATE decks_subcategories SET subcategory_id = :sub_cat WHERE deck_id = :id');
                $req->execute(array(':id'=>$id, ':sub_cat'=>$subcat));
                return true;
            }else{
                throw new Exception('Der Name enthält mindestens ein ungültiges Zeichen.');
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
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
        $date = date(self::$date_formate, strtotime($this->date));
        return $date;
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
    
    public function getCategory() {
        return $this->category;
    }
    
    public function getSubcategory() {
        return $this->subcategory;
    }
    
    public function getCategoryName() {
        return $this->category_name;
    }
    
    public function getSubcategoryName() {
        return $this->subcategory_name;
    }
    
    public function getDeckpageUrl() {
        return Routes::getUri('deck_detail_page').'?id='.$this->id;
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
    
    public function getDeckView() {
        $deck = '';
        require_once PATH.'models/setting.php';
        $cards_per_row = Setting::getByName('deckpage_cards_per_row')->getValue();
        $decksize = Setting::getByName('cards_decksize')->getValue();
        
        $card_images = $this->getImages();
        unset($card_images['master']);
        
        $counter = 0;
        foreach($card_images as $image){
            $counter++;
            $deck.= $image;
            if($counter%$cards_per_row == 0 AND $counter != $decksize){
                $deck.= "<br>";
            }
        } 
        return $deck;
    }
    
    public function getMasterCard() {
        $card_images = $this->getImages();
        return $card_images['master'];
    }
    
    //TODO: improvements: get and display collectors
    
}