<?php
/**
 * 
 * Represents a Masterd Deck
 * 
 * @author Cari
 *
 */

class Master extends DbRecordModel {
    
    protected $id, $deck, $member, $date, $utc;
    
    private $deck_obj, $member_obj, $counter;
    
    protected static
        $db_table = 'decks_master',
        $db_pk = 'id',
        $db_fields = array('id','member','deck','date','utc'),
        $sql_order_by_allowed_values = array('id','member_name','name','master_date','deckname','date');
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getMasteredByMember($member_id, $grouped = true, $order_settings = ['deckname'=>'ASC']) {
        
        $mastered_decks = array();
        $db = DB::getInstance();
        
        $sql_group = 'GROUP BY dm.id';
        if($grouped){
            $sql_group = 'GROUP BY d.id';
        }
        $sql_order = self::buildSqlPart('order_by',$order_settings);
        
        $query = 'SELECT dm.*, MIN(dm.date) as date, COUNT(dm.id) as counter
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
        
        $query = 'SELECT dm.date as master_date ,master.id, master.name, master.mail, 
				master.info_text, master.info_text_html, master.level, master.money, master.join_date, master.login_date, master.status
                FROM decks_master dm
                JOIN members master ON master.id = dm.member
                WHERE dm.deck = :deck_id '.$sql_group.' '.$sql_order['query_part'];
        $req = $db->prepare($query);
        $req->execute(array(':deck_id'=>$deck_id));
        if($req->rowCount() > 0){
        	//foreach($req->fetchAll(PDO::FETCH_CLASS,'Member') as $member){
        	foreach($req->fetchAll(PDO::FETCH_ASSOC) as $result){
        		$member = new Member();
                $members[] = $member->setPropValues($result);
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
    
    public function getDate($timezone=DEFAULT_TIMEZONE) {
    	$date = new DateTime($this->utc);
    	$date->setTimezone(new DateTimeZone($timezone));
        return $date->format(Setting::getByName('date_format')->getValue());
    }
    
    public function getPossessionCounter(){
        return $this->counter;
    }
}