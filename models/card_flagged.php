<?php
/**
 * 
 * RepreseCnts a Card with Flags
 * 
 * @author Cari
 *
 */
require_once PATH.'models/card.php';

class CardFlagged extends Card {
    
    protected $keep_flag, $collect_flag, $mastered_flag, $in_keep_flag, $in_collect_flag, $possession_counter;    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function missingInKeep(){
        if($this->missingInCollect() OR $this->keep_flag == 0 OR $this->in_keep_flag == 1){
            return false;
        }else{
            return true;
        }
    }
    
    public function missingInCollect(){
        if($this->collect_flag == 0 OR $this->in_collect_flag == 1){
            return false;
        }else{
            return true;
        }
    }
    public function deckInKeep(){
        if(!$this->keep_flag){
            return false;
        }else{
            return true;
        }
    }
    public function mastered(){
        if(!$this->mastered_flag){
            return false;
        }else{
            return true;
        }
    }
    
    /** 
     * determins flags for the current card
     */
    public function flag($compare_user_id,$query_style='join'){
        
        if(!is_null($compare_user_id) AND $compare_user_id != $this->getOwnerId()){
            
            
            if(!is_null($this->id)){
                // on default use card id to set flags
                switch($query_style){
                    case 'exists':
                        $sql = "SELECT c.*,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'collect' and deck = c.deck) as collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'keep' and deck = c.deck) as keep_flag,
                                	EXISTS (SELECT 1 FROM decks_master WHERE member = ".$compare_user_id." and deck = c.deck) as mastered_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'collect' and deck = c.deck AND number = c.number) as in_collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'keep' and deck = c.deck AND number = c.number) as in_keep_flag
                                    FROM cards c
                                    WHERE c.id = ".$this->id;
                        break;
                    default:
                        $sql = "SELECT c.*, collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                                    FROM cards c
                                    LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'collect')
                                        collect ON collect.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'keep')
                                        keep ON keep.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                        mastered ON mastered.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'collect')
                                        in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                                    LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'keep')
                                        in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number
                                    WHERE c.id = ".$this->id;
                        break;
                }
                
            }elseif(!is_null($this->deck) AND !is_null($this->number)){
                switch($query_style){
                    case 'exists':
                        $sql = "SELECT 
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'collect' and deck = c.deck) as collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'keep' and deck = c.deck) as keep_flag,
                                	EXISTS (SELECT 1 FROM decks_master WHERE member = ".$compare_user_id." and deck = c.deck) as mastered_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'collect' and deck = c.deck AND number = c.number) as in_collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'keep' and deck = c.deck AND number = c.number) as in_keep_flag
                                    FROM cards.c
                                    WHERE c.deck = ".$this->deck." AND c.number = ".$this->number;
                        break;
                    default:
                        $sql = "SELECT collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                                    FROM cards c
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'collect')
                                        collect ON collect.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'keep')
                                        keep ON keep.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                        mastered ON mastered.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'collect')
                                        in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                                    LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'keep')
                                        in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number
                                    WHERE c.deck = ".$this->deck." AND c.number = ".$this->number;
                        break;
                }
            }
            
            $req = $this->db->query($sql);
            $card = $req->fetch(PDO::FETCH_ASSOC);
            
            $this->setPropValues($card);
            
            
        }else{
            // set all flags to 0;
            $this->keep_flag = 0;
            $this->collect_flag = 0;
            $this->mastered_flag = 0;
            $this->in_keep_flag = 0;
            $this->in_collect_flag = 0;
        }
        return $this;
    }
    
    
    public static function getCardsByStatus($user_id, $status, $grouped = true, $compare_user_id = NULL, $query_style = 'join') {
        if(!in_array($query_style, ['exists','join'])){
            $query_style = 'join';
        }
        if(!in_array($status, self::getAcceptedStati())){
            $stati = self::getAcceptedStati();
            $status = $stati[0];
        }
        if(is_null($compare_user_id) AND Login::loggedIn()){
            $compare_user_id = Login::getUser()->getId();
        }
        $user_id = intval($user_id);
        $cards = array();
        $db = DB::getInstance();
        
        
        if(!is_null($compare_user_id) AND $compare_user_id != $user_id AND $status == 'trade'){
            
            switch($query_style){
                case 'exists':
                    $sql = "SELECT MIN(c.id), c.*, count(c.id) as possession_counter,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'collect' and deck = c.deck) as collect_flag,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'keep' and deck = c.deck) as keep_flag,
                            	EXISTS (SELECT 1 FROM decks_master WHERE member = ".$compare_user_id." and deck = c.deck) as mastered_flag,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'collect' and deck = c.deck AND number = c.number) as in_collect_flag,
                            	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status = 'keep' and deck = c.deck AND number = c.number) as in_keep_flag
                                FROM cards c
                                LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                                WHERE c.owner = $user_id AND c.status = '$status' AND t.id IS NULL
                                GROUP BY name
                                ORDER BY name ASC";
                    break;
                default:
                    $sql = "SELECT MIN(c.id), c.*, count(c.id) as possession_counter, collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                                FROM cards c
                                LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                                LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'collect')
                                    collect ON collect.deck = c.deck
                                LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'keep')
                                    keep ON keep.deck = c.deck
                                LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                    mastered ON mastered.deck = c.deck
                                LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'collect')
                                    in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                                LEFT JOIN (SELECT DISTINCT deck, number, status, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status = 'keep')
                                    in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number
                                WHERE c.owner = $user_id AND c.status = '$status' AND t.id IS NULL
                                GROUP BY name
                                ORDER BY name ASC, id ASC";
                    break;
            }
            
        }else{
            // just grouped no flags!
            $sql = "SELECT MIN(c.id), c.*, count(c.id) as possession_counter
                    FROM cards c
                    LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                    WHERE c.owner = $user_id AND c.status = '$status' AND t.id IS NULL
                    GROUP BY name
                    ORDER BY name ASC, id ASC";
        }
        
        $req = $db->query($sql);
        
        foreach($req->fetchALL(PDO::FETCH_CLASS,__CLASS__) as $card){
            $cards[] = $card;
        }
        
        return $cards;
    }
    
    public function getPossessionCounter(){
        return $this->possession_counter;
    }
    
    public function getSortingOptions(){
        $options = array();
        $options[] = array('name'=>'','value'=>'','prop_selected'=>false);
        foreach(self::getAcceptedStati() as $status){
            $option = array();
            if($this->status != $status){
                switch($status){
                    case 'new': 
                        break;
                    case 'keep':
                        $option['name'] = $status;
                        $option['value'] = $status;
                        if($this->missingInKeep() AND $this->status == 'new'){
                            $option['prop_selected'] = true;
                        }else{
                            $option['prop_selected'] = false;
                        }
                        $options[] = $option;
                        break;
                    case 'collect':
                        if($this->in_collect_flag == 0){
                            $option['name'] = $status;
                            $option['value'] = $status;
                            if($this->missingInCollect() AND $this->status == 'new'){
                                $option['prop_selected'] = true;
                            }else{
                                $option['prop_selected'] = false;
                            }
                            $options[] = $option;
                        }
                        break;
                    case 'trade':
                        $option['name'] = $status;
                        $option['value'] = $status;
                        if(!$this->missingInCollect() AND !$this->missingInKeep() AND $this->status == 'new'){
                            $option['prop_selected'] = true;
                        }else{
                            $option['prop_selected'] = false;
                        }
                        $options[] = $option;
                        
                        break;
                    default;
                        $option['name'] = $status;
                        $option['value'] = $status;
                        $option['prop_selected'] = false;
                        $options[] = $option;
                        break;
                }
            }
        }
        return $options;
    }
    
    public function getSortingOptionsHTML(){
        $html = '';
        foreach($this->getSortingOptions() as $option){
            $html.= '<option value="'.$option['value'].'"';
            if($option['prop_selected']){
                $html.= ' selected';
            }
            $html.= '>'.$option['name'].'</option>\n';
        }
        return $html;
    }
    
}