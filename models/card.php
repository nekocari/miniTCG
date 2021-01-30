<?php
/**
 * 
 * Represents a Card
 * 
 * @author Cari
 *
 */
require_once PATH.'models/setting.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/tradelog.php';
require_once PATH.'models/member.php';

class Card {
    
    private $id;
    private $name;
    private $deck_id;
    private $card_no;
    private $owner;
    private $status;
    private $date;
    private $db;
    private static $tpl_width;
    private static $tpl_height;
    private static $tpl_html;
    public static $accepted_status = array('new','trade','keep','collect');
    
    public function __construct($id, $name, $deck_id, $card_no, $owner, $status, $date) {
        $this->id           = $id;
        $this->name         = $name;
        $this->deck_id      = $deck_id;
        $this->card_no      = $card_no;
        $this->owner        = $owner;
        $this->status       = $status;
        $this->date         = $date;
        $this->db           = DB::getInstance();
        if(is_null(self::$tpl_html)){
            self::$tpl_width = Setting::getByName('cards_template_width')->getValue();
            self::$tpl_height = Setting::getByName('cards_template_height')->getValue();
            self::$tpl_html = file_get_contents(PATH.'views/templates/card_image_temp.php');
        }
    }
    
    public static function getById($id) {
        $card = false;
        $db = DB::getInstance();
        
        $req = $db->prepare('SELECT c.*, m.name as owner_name FROM cards c JOIN members m ON m.id = c.owner WHERE c.id = :id');
        $req->execute(array(':id'=>$id));
        if($req->rowCount() > 0){
            $carddata = $req->fetch(PDO::FETCH_OBJ);
            $owner = Member::getById($carddata->owner);
            $card = new Card($carddata->id, $carddata->name, $carddata->deck, $carddata->number, $owner, $carddata->status, $carddata->date);
        }
        return $card;
    }
    
    public static function changeStatusById($id,$status,$user) {
        $db = DB::getInstance();
        if($status == 'collect'){
            $card_name = self::getById($id)->getName();
            $req = $db->prepare('SELECT * FROM cards WHERE owner = :user AND status = :status AND name = :name');
            $req->execute(array(':status'=>$status,':name'=>$card_name,':user'=>$user));
            if($req->rowCount() > 0){
                return false;
            }
        }
        $req = $db->prepare('UPDATE cards SET status = :status WHERE id = :id and owner = :user');
        return $req->execute(array(':status'=>$status,':id'=>$id,':user'=>$user));
    }
    
    public static function dissolveCollection($deck_id,$user) {
        $db = DB::getInstance();
        $req = $db->prepare('UPDATE cards SET status = \'new\' WHERE deck = :deck_id and owner = :user and status = \'collect\'');
        return $req->execute(array(':deck_id'=>$deck_id,':user'=>$user));
    }
    
    // TODO: MOVE TO MEMBER CLASS?
    public static function getMemberCardsByStatus($user_id, $status, $only_tradeable = false) {
        $cards = array();
        $db = DB::getInstance();
        
        $req = $db->prepare('SELECT * FROM cards WHERE owner = :user_id AND status = :status ORDER BY name ASC');
        $req->execute(array(':user_id'=>$user_id, ':status'=>$status));
        foreach($req->fetchAll(PDO::FETCH_OBJ) as $carddata){
            $owner = Member::getById($carddata->owner);
            $card = new Card($carddata->id, $carddata->name, $carddata->deck, $carddata->number, $owner, $carddata->status, $carddata->date);
            if(!$only_tradeable OR ($only_tradeable AND $card->isTradeable())){
                $cards[] = $card;
            }
        }
        return $cards;
    }
    
    public function store() {
        $req = $this->db->prepare('UPDATE cards SET name = :name, deck = :deck, number = :number, owner = :owner, status = :status, date = :date WHERE id = :id ');
        $req->execute(array(':name'=>$this->name, ':deck'=>$this->deck_id, ':number'=>$this->card_no, ':status'=>$this->status, ':owner'=>$this->owner->getId(), ':date'=>$this->date, ':id'=>$this->id));
        if($req->rowCount() > 0){
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
    
    public function getDate() {
        return $this->date;
    }
    
    public function getDeckId() {
        return $this->deck_id;
    }
    
    public function getNumber() {
        return $this->card_no;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getOwner() {
        return $this->owner;
    }
    
    public function setStatus($status) {
        if(in_array($status, self::$accepted_status)){
            $this->status = $status;
            return true;
        }else{
            return false;
        }
    }
    
    public function setOwner($member_id) {
        $this->owner = Member::getById($member_id);
        return true;
    }
    
    public function getDeckname() {
        $cardname_length = strlen($this->name);
        $cardnumber_length = strlen($this->card_no);
        return substr($this->name,0,-$cardnumber_length);
        return $deck->getDeckname();
    }
    
    public function getImageUrl() {
        $setting_file_type = Setting::getByName('cards_file_type');
        $deckname = $this->getDeckname();
        $url = CARDS_FOLDER.$deckname.'/'.$deckname.$this->card_no.'.'.$setting_file_type->getValue();
        return $url;
    }
    
    public function getImageHtml() {
        $url = $this->getImageUrl();
        
        $tpl_placeholder = array('[WIDTH]','[HEIGHT]','[URL]');
        $replace = array(self::$tpl_width, self::$tpl_height, $url);
        
        return str_replace($tpl_placeholder, $replace, self::$tpl_html);
    }
    
    public static function getSerachcardHtml($img_url = NULL) {
        $url = CARDS_FOLDER.'searchcards/1.png';
        
        $tpl_placeholder = array('[WIDTH]','[HEIGHT]','[URL]');
        $replace = array(self::$tpl_width, self::$tpl_height, $url);
        
        return str_replace($tpl_placeholder, $replace, self::$tpl_html);
    }
    
    public function isTradeable() {
        $tradeable = false;
        if($this->status == 'trade'){
            $query = 'SELECT count(*) FROM trades WHERE (offered_card = '.$this->id.' OR requested_card = '.$this->id.') AND status = \'new\' ';
            $trades = $this->db->query($query)->fetchColumn();
            if($trades == 0){
                $tradeable = true;
            }
        }
        return $tradeable;
    }
    
    public static function createRandomCard($user_id,$number=1,$tradelog_text = '') {
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
            // insert created data to insert into DB for a new Card Object
            $card['number'] = mt_rand(1,$decksize);
            $card['name'] = $deckdata->deckname.$card['number'];
            $card['date'] = date('Y-m-d G:i:s');
            $req = $db->prepare('INSERT INTO cards (owner,deck,number,name,date) VALUES (:user_id, :deck_id, :number, :name, :date) ');
            $req->execute(array(':user_id'=>$user_id, ':deck_id'=>$deckdata->id, ':number'=>$card['number'], ':name'=>$card['name'], ':date'=>$card['date']));
            $card['id'] = $db->lastInsertId();
            
            $cards[] = new Card($card['id'], $card['name'], $deckdata->id, $card['number'], $user_id, 'new', $card['date']);
            
            $log_text = $tradelog_text.' -> '.$card['name'].' (#'.$card['id'].') erhalten.';
            Tradelog::addEntry($user_id, $log_text);
        }
        Member::getById($user_id)->checkLevelUp();
        if($number == 1){ 
            return $cards[0]; 
        }else{
            return $cards;
        }
    }
    
    public static function takeCardsFromUpdate($user_id,$update_id) {
        $cards = array();
        $db = Db::getInstance();
         
        try{
            $update_decks = Carddeck::getInUpdate($update_id);
            $decksize = Setting::getByName('cards_decksize')->getValue();
            $db->beginTransaction();
            foreach($update_decks as $deck){
                // insert created data to insert into DB for a new Card Object
                $card['number'] = mt_rand(1,$decksize);
                $card['name'] = $deck->getDeckname().$card['number'];
                $card['date'] = date('Y-m-d G:i:s');
                
                $req = $db->prepare('INSERT INTO cards (owner,deck,number,name,date) VALUES (:user_id, :deck_id, :number, :name, :date) ');
                $req->execute(array(':user_id'=>$user_id, ':deck_id'=>$deck->getId(), ':number'=>$card['number'], ':name'=>$card['name'], ':date'=>$card['date']));
                $cards[] = new Card($db->lastInsertId(), $card['name'], $deck->getId(), $card['number'], $user_id, 'new', $card['date']);
            }
            if(count($cards) > 0){
                $req = $db->prepare('INSERT INTO updates_members (update_id, member_id) VALUES (:update_id,:user_id) ');
                $req->execute(array(':update_id'=>$update_id,':user_id'=>$user_id));
            }
            
            // add entry for each card in member tradelog
            foreach($cards as $card){
                Tradelog::addEntry($user_id, 'Cardupdate -> '.$card->getName().' (#'.$card->getId().') erhalten.');
            }
            
            $db->commit();
            Member::getById($user_id)->checkLevelUp();
            return $cards;
        }
        catch(Exception $e) {
            $db->rollBack();
            return $e->getMessage();
        }
        
    }
    
    public static function findTrader($deck_id, $number){
        
        $db = Db::getInstance();
        $trader = array();
        
        $query = 'SELECT DISTINCT members.*, min(cards.id) as card_id FROM cards 
                JOIN members ON members.id = owner
                LEFT JOIN trades ON trades.status = \'new\' AND (requested_card = cards.id OR offered_card = cards.id)
                WHERE trades.status IS NULL AND deck= ? AND number = ?
                GROUP BY members.id 
                ORDER BY members.name ASC
                ';
        $req = $db->prepare($query);
        if($req->execute([$deck_id,$number])){
            foreach($req->fetchALL(PDO::FETCH_CLASS,'Member') as $member){
                
                $trader[$member->card_id] = $member; 
                
            }
        }
        
        return $trader;
    }
}