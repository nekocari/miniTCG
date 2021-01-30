<?php
/**
 * 
 * Represents a Masterd Deck
 * 
 * @author Cari
 *
 */
require_once PATH.'models/member.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/setting.php';

class Master {
    
    private $id;
    private $deck_id;
    private $deck;
    private $member_id;
    private $member;
    private $date;
    private static $allowed_order_by = array('deckname','master_date','name');
    private static $allowed_order = array('ASC','DESC');
    
    public function __construct($deck_id,$member_id,$date) {
        $this->deck_id = $deck_id;
        $this->member_id = $member_id;
        $this->date = $date;
    }
    
    public static function getMasterdByMember($member_id,$order_by='deckname',$order='ASC') {
        $mastered_decks = array();
        $db = DB::getInstance();
        if(!in_array($order_by, self::$allowed_order_by)) $order_by = 'deckname';
        if(!in_array($order, self::$allowed_order)) $order = 'ASC';
        
        $query = 'SELECT d.*, 
                    dm.date as master_date,
                    master.id as master_member_id
                FROM decks_master dm
                JOIN decks d ON d.id = dm.deck
                JOIN members master ON master.id = dm.member
            WHERE dm.member = :member_id ORDER BY '.$order_by.' '.$order;
        $req = $db->prepare($query);
        $req->execute(array(':member_id'=>$member_id));
        if($req->rowCount() > 0){
            
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $master){
                
                $mastered_decks[] = new Master($master->id, $master->master_member_id, $master->master_date);
            }
        }
        return $mastered_decks;
    }
    
    public static function getMemberByDeck($deck_id,$order_by='name',$order='ASC') {
        $members = array();
        $db = DB::getInstance();
        if(!in_array($order_by, self::$allowed_order_by)) $order_by = 'name';
        if(!in_array($order, self::$allowed_order)) $order = 'ASC';
        
        $query = 'SELECT dm.date as master_date,master.*
                FROM decks_master dm
                JOIN members master ON master.id = dm.member
            WHERE dm.deck = :deck_id ORDER BY '.$order_by.' '.$order;
        $req = $db->prepare($query);
        $req->execute(array(':deck_id'=>$deck_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,'Member') as $member){
                $members[] = $member;
            }
        }
        var_dump($members);
        return $members;
    }
    
    public function getDeck() {
        if(!$this->deck instanceof Carddeck){
            $this->deck = Carddeck::getById($this->deck_id);
        }
        return $this->deck;
    }
    
    public function getMember() {
        if(!$this->member instanceof Member){
            $this->member = Member::getById($this->member_id);
        }
        return $this->member;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
}