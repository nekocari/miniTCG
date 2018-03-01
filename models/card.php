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
    
    public static function createRandomCard($user_id,$number=1) {
        require_once 'models/setting.php';
        if(intval($number) < 0){ 
            throw new Exception('Keine gültige Anzahl übergeben!');
        }
        $cards = array();
        $decksize = Setting::getByName('cards_decksize')->getValue();
        $db = DB::getInstance();
        
        for($i = 0; $i < $number; $i++){
            // grab deck data randomly
            $req = $db->query('SELECT id, deckname FROM decks WHERE status = \'public\' ORDER BY RAND() LIMIT 1');
            $deckdata = $req->fetch(PDO::FETCH_OBJ);
            // insert create data to insert into DB for a new Card Object
            $card['number'] = mt_rand(1,$decksize);
            $card['name'] = $deckdata->deckname.$card['number'];
            $card['date'] = date('Y-m-d G:i:s');
            $req = $db->prepare('INSERT INTO cards (owner,deck,number,name,date) VALUES (:user_id, :deck_id, :number, :name, :date) ');
            $req->execute(array(':user_id'=>$user_id, ':deck_id'=>$deckdata->id, ':number'=>$card['number'], ':name'=>$card['name'], ':date'=>$card['date']));
            
            $cards[] = new Card($db->lastInsertId(), $card['name'], $deckdata->deckname, $card['number'], $user_id, 'new', $card['date']);
        }
        if($number == 1){ 
            return $cards[1]; 
        }else{
            return $cards;
        }
    }
}