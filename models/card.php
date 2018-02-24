<?php
/**
 * 
 * Represents a Card
 * 
 * @author Cari
 *
 */

class Card {
    
    private $id;
    private $name;
    private $deck_id;
    private $card_no;
    private $owner;
    private $owner_name;
    private $status;
    private $date;
    private $db;
    
    public function __construct($id, $name, $deck_id, $card_no, $owner, $status, $date) {
        $this->id           = $id;
        $this->name         = $name;
        $this->deck_id      = $deck_id;
        $this->card_no      = $card_no;
        $this->owner        = $owner;
        $this->status       = $status;
        $this->date         = $date;
        $this->db           = DB::getInstance();
    }
    
    public static function getById($id) {
        $card = false;
        $db = DB::getInstance();
        
        $req = $db->prepare('SELECT c.*, m.name as owner_name FROM cards c JOIN members m ON m.id = c.owner WHERE c.id = :id');
        $req->execute(array(':id'=>$id));
        if($req->rowCount() > 0){
            $carddata = $req->fetch(PDO::FETCH_OBJ);
            $card = new Card($carddata->id, $carddata->name, $carddata->deck, $carddata->number, $carddata->owner, $carddata->status, $carddata->date);
        }
        return $card;
    }
    
    public function setOwnerName($name) {
        $this->owner_name = $name;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getDeckname() {
        require_once PATH.'models/carddeck.php';
        $deck = Carddeck::getById($this->deck_id);
        return $deck->getDeckname();
    }
    
    public function getImageUrl() {
        require_once PATH.'models/setting.php';
        $setting_file_type = Setting::getByName('cards_file_type'); 
        $deckname = $this->getDeckname();
        $url = CARDS_FOLDER.'/'.$deckname.'/'.$deckname.'.'.$setting_file_type->getValue();
        return $this->deckname;
    }
}