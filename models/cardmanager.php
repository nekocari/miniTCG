<?php
/**
 * 
 * Represents a Cardmanager
 * 
 * @author Cari
 *
 */
require_once PATH.'models/carddeck.php';
require_once PATH.'models/card_cardmanager.php';
require_once PATH.'models/member.php';

class Cardmanager {
      
    private static $accepted_stati;
    private static $accepted_query_styles = array('join','exists');
    private $cards, $member_id, $db, $query_style;
    
    public function __construct($member_id,$query_style = 'join') {
        $this->member_id = $member_id;
        $this->db = DB::getInstance();
        $this->setQueryStyle($query_style);
    }
    
    public static function getAcceptedStati(){
        return Card::getAcceptedStati();
    }
    
    public function setQueryStyle($style){
        if(in_array($style, self::$accepted_query_styles)){
            $this->query_style = $style;
        }
    }
    
    public function dissolveCollection() {
    }
    
    public function getCardsByStatus($status,$trade_locked=false) {
        if(!Login::loggedIn()){
            throw new ErrorException('Can not use method unless user is logged in!');
            return null;
        }
        
        // validate status
        if(!in_array($status, self::getAcceptedStati())){
            throw new ErrorException('status is invalid');
            return null;
        }
        
        // differenciate between tradeable an non tradeable cards
        $join_partial = '';
        $where_partial = '';
        if(!$trade_locked){
            $join_partial = "LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'";
            $where_partial = "AND t.id IS NULL";
        }
        
        // check if array element does already exist
        // if not try to get the values from the Database
        if(!isset($cards[$status][$trade_locked])){
            $cards[$status][$trade_locked] = array();
            switch($this->query_style){
                case 'exists':
                    $sql = "SELECT c.*,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status = 'collect' and deck = c.deck) as collect_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status = 'keep' and deck = c.deck) as keep_flag,
                        	EXISTS (SELECT 1 FROM decks_master WHERE member = c.owner and deck = c.deck) as mastered_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status = 'collect' and deck = c.deck AND number = c.number) as in_collect_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status = 'keep' and deck = c.deck AND number = c.number) as in_keep_flag
                            FROM cards c $join_partial
                            WHERE c.owner = ".Login::getUser()->getId()." AND c.status = '$status' $where_partial
                            ORDER BY name ASC";
                    break;
                default:
                    $sql = "SELECT c.*, collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                            FROM cards c $join_partial
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".Login::getUser()->getId()." AND status = 'collect')
                                collect ON collect.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".Login::getUser()->getId()." AND status = 'keep')
                                keep ON keep.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".Login::getUser()->getId().")
                                mastered ON mastered.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".Login::getUser()->getId()." AND status = 'collect')
                                in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                            LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".Login::getUser()->getId()." AND status = 'keep')
                                in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number
                            WHERE c.owner = ".Login::getUser()->getId()." AND c.status = '$status' $where_partial
                            ORDER BY name ASC";
                    break;
            }
            
            $req = $this->db->query($sql);
            
            foreach($req->fetchALL(PDO::FETCH_CLASS,'CardCardmanager') as $card){
                $cards[$status][$trade_locked][] = $card;
            }
            
        }
        
        // return an array of card objects
        return $cards[$status][$trade_locked];
    }
}