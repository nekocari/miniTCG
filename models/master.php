<?php
/**
 * 
 * Represents a Masterd Deck
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/member.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/setting.php';

class Master extends DbRecordModel {
    
    protected $id, $deck, $member, $date;
    
    private $deck_obj, $member_obj, $counter;
    
    protected static
        $db_table = 'decks_master',
        $db_pk = 'id',
        $db_fields = array('id','member','deck','date'),
        $sql_order_by_allowed_values = array('id','member_name','name','master_date','deckname','date');
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getMasterdByMember($member_id, $grouped = true, $order_settings = ['deckname'=>'ASC']) {
        
        $mastered_decks = array();
        $db = DB::getInstance();
        
        $sql_group = 'GROUP BY dm.id';
        if($grouped){
            $sql_group = 'GROUP BY d.id';
        }
        $sql_order = self::buildSqlPart('order_by',$order_settings);
        
        $query = 'SELECT MIN(dm.date), dm.*, COUNT(dm.id) as counter
                FROM decks_master dm
                JOIN decks d ON d.id = dm.deck
                WHERE dm.member = :member_id '.$sql_group.' '.$sql_order['query_part'];
        $req = $db->prepare($query);
        $req->execute(array(':member_id'=>$member_id));
        
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $master){
                $mastered_decks[] = $master;
            }
        }
        
        return $mastered_decks;
    }
    
    public static function getMemberByDeck($deck_id, $grouped = true, $order_settings = ['name'=>'ASC']) {
        $members = array();
        $db = DB::getInstance();
        
        $sql_group = '';
        if($grouped){
            $sql_group = 'GROUP BY master.id';
        }
        $sql_order = self::buildSqlPart('order_by',$order_settings);
        
        $query = 'SELECT dm.date as master_date,master.*
                FROM decks_master dm
                JOIN members master ON master.id = dm.member
                WHERE dm.deck = :deck_id '.$sql_group.' '.$sql_order['query_part'];
        $req = $db->prepare($query);
        $req->execute(array(':deck_id'=>$deck_id));
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_CLASS,'Member') as $member){
                $members[] = $member;
            }
        }
        return $members;
    }
    
    public function getDeck() {
        if(!$this->deck_obj instanceof Carddeck){
            $this->deck_obj = Carddeck::getById($this->deck);
        }
        return $this->deck_obj;
    }
    
    public function getMember() {
        if(!$this->member_obj instanceof Member){
            $this->member_obj = Member::getById($this->member);
        }
        return $this->member_obj;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getPossessionCounter(){
        return $this->counter;
    }
}