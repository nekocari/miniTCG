<?php
/**
 * 
 * Represents a Card on Member profil page
 * 
 * @author Cari
 *
 */

require_once PATH.'models/card_cardmanager.php';

class CardMemberProfil extends CardCardmanager {
    
    private $possession_counter;    
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getCardsByStatus($user_id, $status, $query_style = 'join') {
        if(!in_array($query_style, ['exists','join'])){
            $query_style = 'join';
        }
        if(!in_array($status, self::getAcceptedStati())){
            $stati = self::getAcceptedStati();
            $status = $stati[0];
        }
        $user_id = intval($user_id);
        $cards = array();
        $db = DB::getInstance();
        
        if(Login::loggedIn() AND $status == 'trade'){
        
            switch($query_style){
                case 'exists':
                    $sql = "SELECT c.*,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".Login::getUser()->getId()." and status = 'collect' and deck = c.deck) as collect_flag,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".Login::getUser()->getId()." and status = 'keep' and deck = c.deck) as keep_flag,
                            	EXISTS (SELECT 1 FROM decks_master WHERE member = ".Login::getUser()->getId()." and deck = c.deck) as mastered_flag,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".Login::getUser()->getId()." and status = 'collect' and deck = c.deck AND number = c.number) as in_collect_flag,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".Login::getUser()->getId()." and status = 'keep' and deck = c.deck AND number = c.number) as in_keep_flag
                                FROM cards c 
                                LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                                WHERE c.owner = $user_id AND c.status = '$status' AND t.id IS NULL
                                ORDER BY name ASC";
                    break;
                default:
                    $sql = "SELECT c.*, count(c.id) as possession_counter, collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                                FROM cards c 
                                LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
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
                                WHERE c.owner = $user_id AND c.status = '$status' AND t.id IS NULL
                                GROUP BY name
                                ORDER BY name ASC, id ASC";
                    break;
            }
            
        }else{
            // just grouped no flags!
            $sql = "SELECT c.*, count(c.id) as possession_counter
                    FROM cards c 
                    LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                    WHERE c.owner = $user_id AND c.status = '$status' AND t.id IS NULL
                    GROUP BY name
                    ORDER BY name ASC, id ASC";
        }
        
        $req = $db->query($sql);
        
        foreach($req->fetchALL(PDO::FETCH_CLASS,'CardMemberProfil') as $card){
            $cards[] = $card;
        }
        
        return $cards;
    }
    
    public function getPossessionCounter(){
        return $this->possession_counter;
    }
    
}