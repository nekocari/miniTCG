<?php
/**
 * 
 * RepreseCnts a Card with Flags
 * 
 * @author NekoCari
 *
 */


class CardFlagged extends Card {
    
    protected $keep_flag, $collect_flag, $mastered_flag, $in_keep_flag, $in_collect_flag, $owned_flag, $possession_counter=1;    
    
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
    public function owned(){
    	if(!$this->owned_flag){
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
        	
        	//$status_id_new = CardStatus::getNew()->getId();
        	$status_id_collect = CardStatus::getCollect()->getId();
        	$status_ids_not_tradeable_str = 'NULL,';
        	$status_not_tradeable = CardStatus::getNotTradeable();
        	foreach($status_not_tradeable as $status_nt){
        		$status_ids_not_tradeable_str.= $status_nt->getId().',';
        	}
        	$status_ids_not_tradeable_str = substr($status_ids_not_tradeable_str,0,-1);
            
            if(!is_null($this->id)){
                // on default use card id to set flags
                switch($query_style){
                    case 'exists':
                        $sql = "SELECT c.*,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id = $status_id_collect and deck = c.deck) as collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id IN($status_ids_not_tradeable_str) and deck = c.deck) as keep_flag,
                                	EXISTS (SELECT 1 FROM decks_master WHERE member = ".$compare_user_id." and deck = c.deck) as mastered_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id = $status_id_collect and deck = c.deck AND number = c.number) as in_collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_keep_flag
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and deck = c.deck AND number = c.number) as owned_flag
                                    FROM cards c
                                    WHERE c.id = ".$this->id;
                        break;
                    default:
                        $sql = "SELECT c.*, collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                                    FROM cards c
                                    LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = $status_id_collect)
                                        collect ON collect.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str))
                                        keep ON keep.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                        mastered ON mastered.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = $status_id_collect)
                                        in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                                    LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str))
                                        in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number
                                    LEFT JOIN (SELECT DISTINCT deck, number, 1 as flag FROM cards WHERE owner = ".$compare_user_id.")
                                        owned ON owned.deck = c.deck AND owned.number = c.number 
                                    WHERE c.id = ".$this->id;
                        break;
                }
                
            }elseif(!is_null($this->deck) AND !is_null($this->number)){
                switch($query_style){
                    case 'exists':
                        $sql = "SELECT 
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id = $status_id_collect and deck = c.deck) as collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id IN($status_ids_not_tradeable_str) and deck = c.deck) as keep_flag,
                                	EXISTS (SELECT 1 FROM decks_master WHERE member = ".$compare_user_id." and deck = c.deck) as mastered_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id = $status_id_collect and deck = c.deck AND number = c.number) as in_collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_keep_flag
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and deck = c.deck AND number = c.number) as owned_flag
                                    FROM (SELECT  ".$this->deck." as deck, ".$this->number." as number) c ";
                        break;
                    default:
                        $sql = "SELECT collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag, owned.flag as owned_flag
                                    FROM (SELECT  ".$this->deck." as deck, ".$this->number." as number) c
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = $status_id_collect)
                                        collect ON collect.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str))
                                        keep ON keep.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                        mastered ON mastered.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = $status_id_collect)
                                        in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                                    LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str))
                                        in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number 
                                    LEFT JOIN (SELECT DISTINCT deck, number, 1 as flag FROM cards WHERE owner = ".$compare_user_id.")
                                        owned ON owned.deck = c.deck AND owned.number = c.number ";
                        break;
                }
            }
            $req = $this->db->query($sql);
            $card = $req->fetch(PDO::FETCH_ASSOC);
            if($card !== false){
                $this->setPropValues($card);
            }
            
            
        }else{
            // set all flags to 0;
            $this->keep_flag = 0; // in untradebale status but not collection or new
            $this->collect_flag = 0; 
            $this->mastered_flag = 0;
            $this->in_keep_flag = 0;
            $this->in_collect_flag = 0;
            $this->owned_flag = 0;
        }
        return $this;
    }
    
    
    public function getPossessionCounter(){
        return $this->possession_counter;
    }
    
    public function getSortingOptions(){
        $options = array();
        $options[] = array('name'=>'','value'=>'','prop_selected'=>false);
        foreach(self::getAcceptedStatiObj() as $key=>$status){
            $option = array();
            if($this->getStatusId() != $key){
            	
            	$option['name'] = $status->getName();
            	$option['value'] = $key;
            	$option['prop_selected'] = false;
            	
            	// ( not "new")
            	if(!$status->isNew()){
	            	// ("keep")
	            	if(!$status->isNew() AND !$status->isCollections() AND !$status->isTradeable() AND  $this->missingInKeep() AND $this->getStatus()->isNew()){
	            		$option['prop_selected'] = true;
	            	}
	            	// ("collect")
	            	if($status->isCollections() AND $this->in_collect_flag == 0 AND $this->missingInCollect() AND $this->getStatus()->isNew()){
	            		$option['prop_selected'] = true;
	            	}
	            	// ("trade)
	            	if($status->isTradeable() AND !$this->missingInCollect() AND !$this->missingInKeep() AND $this->getStatus()->isNew()){
	            		$option['prop_selected'] = true;
	            	}
            	
            		$options[] = $option;
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