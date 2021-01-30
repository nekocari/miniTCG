<?php
/**
 * 
 * Represents a Carddeck
 * 
 * @author Cari
 *
 */
require_once PATH.'models/member.php';
require_once PATH.'models/setting.php';

class Carddeck {
    
    private $id;
    private $name;
    private $deckname;
    private $status;
    private $type;
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
    private static $allowed_types = array('default','puzzle');
    private $date_formate = 'd.m.Y';
    
    public function __construct($id, $name, $deckname, $status, $type, $date, $creator_id, $creator_name, $category, $subcategory, $category_name, $subcategory_name) {
        $this->id           = $id;
        $this->name         = $name;
        $this->deckname     = $deckname;
        $this->status       = $status;
        $this->type      = $type;
        $this->creator_id   = $creator_id;
        $this->creator_name = $creator_name;
        $this->date         = $date;
        $this->category     = $category;
        $this->subcategory  = $subcategory;
        $this->category_name     = $category_name;
        $this->subcategory_name  = $subcategory_name;
        $this->db           = DB::getInstance();
        $this->date_formate = Setting::getByName('date_format')->getValue();
    }
    
    public static function getAcceptedTypes(){
        return self::$allowed_types;
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
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->type, $deck->date, $deck->creator, $deck->creator_name, 
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
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->type, $deck->date, $deck->creator, $deck->creator_name,
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
            return new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->type, $deck->date, $deck->creator, $deck->creator_name,
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
                WHERE ds.subcategory_id = :sub and d.status = \'public\' ORDER BY d.id ASC';
        $req = $db->prepare($query);
        $req->execute(array(':sub'=>$sub_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $deck){
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->type, $deck->date, $deck->creator, $deck->creator_name,
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
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->type, $deck->date, $deck->creator, $deck->creator_name,
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
                $decks[] = new Carddeck($deck->id, $deck->name, $deck->deckname, $deck->status, $deck->type, $deck->date, $deck->creator, $deck->creator_name,
                    $deck->cat_id, $deck->sub_id, $deck->cat_name, $deck->sub_name);
            }
        }
        return $decks;
    }
    
    public static function update($id, $name, $creator, $subcat, $type='default') {
        $db = DB::getInstance();
        try{
            if(preg_match(self::$naming_pattern, $name)){
                $req = $db->prepare('UPDATE decks SET name = :name, creator = :creator, type = :type WHERE id = :id');
                $req->execute(array(':id'=>$id, ':name'=>$name, ':creator'=>$creator, ':type'=>$type));
                $req = $db->prepare('UPDATE decks_subcategories SET subcategory_id = :sub_cat WHERE deck_id = :id');
                $req->execute(array(':id'=>$id, ':sub_cat'=>$subcat));
                return true;
            }else{
                throw new Exception('invalid character',1001);
            }
        }
        catch(Exception $e) {
            return $e->getCode();
        }
    }
    
    public function getCollectorMembers(){
        try{
            $members = array();
            $req = $this->db->prepare('SELECT DISTINCT m.* FROM cards c JOIN members m ON m.id = c.owner WHERE deck = '.$this->id.' AND c.status = \'collect\'');
            $req->execute();
            
            foreach($req->fetchAll(PDO::FETCH_CLASS,'Member') as $member){
                $members[] = $member;
            }
            
            return $members;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    
    public static function master($deck, $member){
        require_once PATH.'models/setting.php';
        $db = DB::getInstance();
        
        $gift_cards_num = Setting::getByName('giftcards_for_master')->getValue();
        try{
            $db->beginTransaction();
            // delete cards
            $req = $db->prepare('DELETE FROM cards WHERE owner = :member AND deck = :deck AND status=\'collect\' ');
            $req->execute(array(':deck'=>$deck, ':member'=>$member));
            if($req->rowCount() != Setting::getByName('cards_decksize')->getValue()){
                throw new Exception('Das Deck ist nicht komplett!');
            }
            // add master
            $req = $db->prepare('INSERT INTO decks_master (deck,member) VALUES (:deck,:member) ');
            $req->execute(array(':deck'=>$deck, ':member'=>$member));
            $db->commit();
            // add gift cards for mastering a deck
            if($gift_cards_num > 0){
                Card::createRandomCard($member,$gift_cards_num,'Deck gemastert');
            }
            return true;
        }
        catch(Exception $e) {
            $db->rollBack();
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
        $date = date($this->date_formate, strtotime($this->date));
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
    
    public function getType() {
        return $this->type;
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
    
    public function isPuzzle(){
        $is_puzzle = false;
        if($this->getType() == 'puzzle'){
            $is_puzzle = true;
        }
        return $is_puzzle;
    }
    
    public function getImageUrls() {
        $urls = array();
        require_once PATH.'models/setting.php';
        $setting_file_type = Setting::getByName('cards_file_type')->getValue();
        $setting_cards_decksize = Setting::getByName('cards_decksize')->getValue();
        $deckname = $this->getDeckname();
        for($i = 1; $i <= $setting_cards_decksize; $i++){
            $urls[$i] = CARDS_FOLDER.$deckname.'/'.$deckname.$i.'.'.$setting_file_type;
        }
        $urls['master'] = CARDS_FOLDER.$deckname.'/'.$deckname.'_master.'.$setting_file_type;
        return $urls;
    }
    
    public function getImages() {
        $card_images = array();
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
    
    public function getMasterMembers() {
        require_once PATH.'models/master.php';
        return Master::getMemberByDeck($this->id);
    }
    
    
}