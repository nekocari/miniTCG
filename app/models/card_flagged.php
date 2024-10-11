<?php
/**
 * 
 * RepreseCnts a Card with Flags
 * 
 * @author NekoCari
 *
 */


class CardFlagged extends Card {
    
	protected $not_tradeable_flag, $collect_flag, 
		$in_not_tradeable_flag, $in_collect_flag, 
		$wishlist_flag, $mastered_flag, $owned_flag,
		$custom_flags=array(), $custom_in_flags=array(), 
		$possession_counter=1;    
	
    public function __construct() {
        parent::__construct();
    }
    
    
    public function missingIn($manager_category_id) {
    	if(!$this->deckInStatus($manager_category_id) OR in_array($manager_category_id, $this->custom_in_flags)){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    public function missingInNotTradeable(){
        if($this->missingInCollect() OR $this->not_tradeable_flag_flag == 0 OR $this->in_not_tradeable_flag == 1){
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
    
    public function deckInStatus($manager_category_id) {
    	if(!in_array($manager_category_id, $this->custom_flags)){
    		return false;
    	}else{
    		return true;
    	}
    }
    /**
     * at least one card of deck exists in an untradeable status?
     * @return boolean
     */
    public function deckInNotTradeable(){
    	if(!$this->not_tradeable_flag OR $this->not_tradeable_flag == 0){
    		return false;
    	}else{
    		return true;
    	}
    }
    /**
     * at least one card of deck exists in an collect status?
     * @return boolean
     */
    public function deckInCollect(){
        if(!$this->collect_flag OR $this->collect_flag == 0){
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
    public function onWishlist(){ 
    	if(!$this->wishlist_flag OR $this->wishlist_flag == 0){
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
     */
    
    public function setCustomFlagIds($flag_ids){
    	if(is_array($flag_ids)){
    		foreach($flag_ids as $flag_id){
    			$this->setCustomFlagId($flag_id);
    		}
    	}else{
    		$this->setCustomFlagId($flag_ids);
    	}
    }
    
    private function setCustomFlagId($flag_id){
    	$this->custom_flags[] = intval($flag_id);
    }
    
    public function setCustomInFlagIds($flag_ids){
    	if(is_array($flag_ids)){
    		foreach($flag_ids as $flag_id){
    			$this->setCustomInFlagId($flag_id);
    		}
    	}else{
    		$this->setCustomInFlagId($flag_ids);
    	}
    }
    
    private function setCustomInFlagId($flag_id){
    	$this->custom_in_flags[] = intval($flag_id);
    }
    
    /** 
     * determins flags for the current card
     */
    public function flag($compare_user_id,$query_style='join'){ // BIG TODO! see cardmanager changes for in untradeables
        
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
                                	EXISTS (SELECT 1 FROM members_wishlist WHERE member_id = ".$compare_user_id." and deck_id = c.deck) as wishlist_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id = $status_id_collect and deck = c.deck AND number = c.number) as in_collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_keep_flag
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and deck = c.deck AND number = c.number) as owned_flag
                                    FROM cards c
                                    WHERE c.id = ".$this->id;
                        break;
                    default:
                        $sql = "SELECT c.*, collect.flag as collect_flag, keep.flag as keep_flag, wishlist.flag as wishlist_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag, owned.flag as owned_flag
                                    FROM cards c
                                    LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = $status_id_collect)
                                        collect ON collect.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str))
                                        keep ON keep.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                        mastered ON mastered.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck_id, 1 as flag FROM members_wishlist WHERE member_id = ".$compare_user_id.")
                                        wishlist ON wishlist.deck_id = c.deck
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
                                	EXISTS (SELECT 1 FROM members_wishlist WHERE member_id = ".$compare_user_id." and deck_id = c.deck) as wishlist_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id = $status_id_collect and deck = c.deck AND number = c.number) as in_collect_flag,
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_keep_flag
                                	EXISTS (SELECT 1 FROM cards WHERE owner = ".$compare_user_id." and deck = c.deck AND number = c.number) as owned_flag
                                    FROM (SELECT  ".$this->deck." as deck, ".$this->number." as number) c ";
                        break;
                    default:
                        $sql = "SELECT collect.flag as collect_flag, keep.flag as keep_flag, wishlist.flag as wishlist_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag, owned.flag as owned_flag
                                    FROM (SELECT  ".$this->deck." as deck, ".$this->number." as number) c
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = $status_id_collect)
                                        collect ON collect.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str))
                                        keep ON keep.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.")
                                        mastered ON mastered.deck = c.deck
                                    LEFT JOIN (SELECT DISTINCT deck_id, 1 as flag FROM members_wishlist WHERE member_id = ".$compare_user_id.")
                                        wishlist ON wishlist.deck_id = c.deck
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
            $this->wishlist_flag = 0;
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
        
        $option_selected = false;
        // go throug statis (from highest to lowest priority)
        foreach(array_reverse(self::getAcceptedStatiObj()) as $key=>$status){
            $option = array();
            // skip if card status id = select status id -> dont show current status as option
            // or skip if card status is not new (only pre select options there)
            if($this->getStatusId() != $status->getId() AND !$status->isNew()){
            	
            		
            	$option['name'] = $status->getName();
            	$option['value'] = $status->getId();
            	$option['prop_selected'] = false;
            	
            	// if nothing was selected until now
            	if(!$option_selected){
            		
            		// COLLECT
            		// select status is collect and card is missing in collect
            		if($status->isCollections() AND $this->missingInCollect() ){
            			$option_selected = true;
            			$option['prop_selected'] = true;
            		}
            		// KEEP - UNTRADEABLES
            		// select status is not collections and untradeable and card is missing in this untradeable status
            		if(!$status->isCollections() AND !$status->isTradeable() AND $this->missingIn($status->getId()) ){
            			$option_selected = true;
            			$option['prop_selected'] = true;
            		}
            		// trade might - TRADEABLES
            		// select status is not collections and tradeable and at least one card of deck is in status
            		if(!$status->isCollections() AND $status->isTradeable() AND $this->deckInStatus($status->getId()) ){
            			$option_selected = true;
            			$option['prop_selected'] = true;
            		}
            	}
            	
            	// add to option element to options array
            	$options[] = $option;
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
            $html.= '>'.$option['name'].'</option>';
        }
        return $html;
    }
    
}