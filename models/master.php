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

class Master {
    
    private $deck;
    private $member;
    private $date;
    private static $allowed_order_by = array('deckname','master_date','master_member_name');
    private static $allowed_order = array('ASC','DESC');
    
    public function __construct($deck,$member,$date) {
        $this->deck = $deck;
        $this->member = $member;
        $this->date = $date;
    }
    
    public static function getMasterdByMember($member_id,$order_by='deckname',$order='ASC') {
        $mastered_decks = array();
        $db = DB::getInstance();
        if(!in_array($order_by, self::$allowed_order_by)) $order_by = 'deckname';
        if(!in_array($order, self::$allowed_order)) $order = 'ASC';
        
        $query = 'SELECT d.*, m.name as creator_name,
                    s.id as sub_id, s.name as sub_name,
                    c.id as cat_id, c.name as cat_name,
                    dm.date as master_date,
                    master.id as master_member_id, master.name as master_member_name, master.level as master_member_level, master.mail as master_member_mail
                FROM decks_master dm
                JOIN decks d ON d.id = dm.deck
                JOIN members m ON m.id = d.creator
                JOIN members master ON master.id = dm.member
                JOIN decks_subcategories ds ON ds.deck_id = d.id
                JOIN subcategories s ON s.id = ds.subcategory_id
                JOIN categories c ON s.category = c.id
            WHERE dm.member = :member_id ORDER BY '.$order_by.' '.$order;
        $req = $db->prepare($query);
        $req->execute(array(':member_id'=>$member_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $master){
                $deck = new Carddeck($master->id, $master->name, $master->deckname, $master->status, $master->date, $master->creator, $master->creator_name,
                    $master->cat_id, $master->sub_id, $master->cat_name, $master->sub_name);
                $member = new Member($master->master_member_id, $master->master_member_name, $master->master_member_level, $master->master_member_mail);
                $mastered_decks[] = new Master($deck, $member, $master->master_date);
            }
        }
        return $mastered_decks;
    }
    
    public static function getMemberByDeck($deck_id,$order_by='master_member_name',$order='ASC') {
        $members = array();
        $db = DB::getInstance();
        if(!in_array($order_by, self::$allowed_order_by)) $order_by = 'master_member_name';
        if(!in_array($order, self::$allowed_order)) $order = 'ASC';
        
        $query = 'SELECT dm.date as master_date,
                    master.id as master_member_id, master.name as master_member_name, master.level as master_member_level, master.mail as master_member_mail
                FROM decks_master dm
                JOIN members master ON master.id = dm.member
            WHERE dm.deck = :deck_id ORDER BY '.$order_by.' '.$order;
        $req = $db->prepare($query);
        $req->execute(array(':deck_id'=>$deck_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $master){
                $members[] = new Member($master->master_member_id, $master->master_member_name, $master->master_member_level, $master->master_member_mail);
            }
        }
        return $members;
    }
    
    public function getDeck() {
        return $this->deck;
    }
    
    public function getMember() {
        return $this->member;
    }
    
    public function getDate() {
        return date(DATE_FORMATE,strtotime($this->date));
    }
}