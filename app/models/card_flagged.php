<?php
/**
 * 
 * RepreseCnts a Card with Flags
 * 
 * @author NekoCari
 *
 */


class CardFlagged extends Card {
    
	protected $not_tradeable_flag = 0, $collect_flag = 0,
		$in_not_tradeable_flag = 0, $in_collect_flag = 0, 
		$wishlist_flag = 0, $mastered_flag = 0, $owned_flag = 0,
		$custom_flags=array(), $custom_in_flags=array(), 
		$possession_counter=1;    
	
    public function __construct() {
        parent::__construct();
    }
    
    
    // MISSING IN FLAG CHECKS
    /**
     * has card missing flag for certain category id?
     * @param int $manager_category_id
     * @return boolean
     */
    public function missingIn($manager_category_id) {
    	if(!$this->deckInStatus($manager_category_id) OR in_array($manager_category_id, $this->custom_in_flags)){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    /**
     * Is card missing in an untradeable category?
     * @return boolean
     */
    public function missingInNotTradeable(){
    	if($this->missingInCollect() OR $this->not_tradeable_flag == 0 OR $this->in_not_tradeable_flag == 1){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    /**
     * @deprecated
     * keep was replaced with not tradeable categories
     * @return boolean
     */
    public function missingInKeep(){
    	return $this->missingInNotTradeable();
    }
    
    /**
     * Is card missing in collection?
     * @return boolean
     */
    public function missingInCollect(){
        if($this->collect_flag == 0 OR $this->in_collect_flag == 1){
            return false;
        }else{
            return true;
        }
    }
    
    // DECK CHECKS
    /**
     * is at least one card of the deck in a certain category?
     * @param int $manager_category_id
     * @return boolean
     */
    public function deckInStatus($manager_category_id) {
    	if(!in_array($manager_category_id, $this->custom_flags)){
    		return false;
    	}else{
    		return true;
    	}
    }
    /**
     * is at least one card of the deck in an untradeable category?
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
     * @deprecated
     * keep was replaced with not tradeable categories
     * @return boolean
     */
    public function deckInKeep(){
    	return $this->deckInNotTradeable();
    }
    /**
     * is this deck currently beeing collected?
     * @return boolean
     */
    public function deckInCollect(){
        if(!$this->collect_flag OR $this->collect_flag == 0){
            return false;
        }else{
            return true;
        }
    }
    
    // MASTER CHECK
    /**
     * is this deck already mastered?
     * @return boolean
     */
    public function mastered(){
    	if(!$this->mastered_flag){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    // WISHLIST CHECK
    /**
     * is this deck on the wishlist?
     * @return boolean
     */
    public function onWishlist(){ 
    	if(!$this->wishlist_flag OR $this->wishlist_flag == 0){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    // DUPLICATE
    /**
     * Is this card owned by compare user (only for check on trade offer pages)
     * @return boolean
     */
    public function owned(){
    	if(!$this->owned_flag){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    /**
     * adds the ids of categories where cards of deck are in
     * @param int[]|int $flag_ids
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
    
    /**
     * adds the ids of categories the card is already in
     * @param int[]|int $flag_ids
     */
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
    public function flag($compare_user_id,$query_style='exists'){
        
        if(!is_null($compare_user_id) AND $compare_user_id != $this->getOwnerId()){
        	
        	// str with ids of stati not tradeable to use in sql in
        	$status_ids_not_tradeable_str = 'NULL,';
        	$status_not_tradeable = CardStatus::getNotTradeable();
        	foreach($status_not_tradeable as $status_nt){
        		$status_ids_not_tradeable_str.= $status_nt->getId().',';
        	}
        	$status_ids_not_tradeable_str = substr($status_ids_not_tradeable_str,0,-1);
        	
        	// deck id and number is needed 
        	if(is_null($this->deck) OR is_null($this->number)){
        		throw new ErrorException();
        	}
        	// get all status (manager category) objects
        	$card_stati = CardStatus::getAll();
			switch($query_style){
				case 'exists':
					$sql_not_tradeables = '';
					foreach($card_stati as $nt_status){
						if(!$nt_status->isNew()){
							$sql_not_tradeables.= "
								EXISTS (SELECT 1 FROM cards WHERE owner = c.owner AND status_id = " . $nt_status->getId() . " AND deck = c.deck AND number = c.number) as in_status" . $nt_status->getId() . "_flag,
								EXISTS (SELECT 1 FROM cards WHERE owner = c.owner AND status_id = " . $nt_status->getId() . " AND deck = c.deck) as status" . $nt_status->getId() . "_flag, ";
						}
					}
                	$sql = "SELECT
								$sql_not_tradeables
                        		EXISTS (SELECT 1 FROM cards WHERE owner =  c.owner AND status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_not_tradeable_flag,
                        		EXISTS (SELECT 1 FROM cards WHERE owner = c.owner AND status_id IN($status_ids_not_tradeable_str) and deck = c.deck) as not_tradeable_flag,
								EXISTS (SELECT 1 FROM decks_master WHERE member = c.owner AND deck = c.deck) as mastered_flag,
								EXISTS (SELECT 1 FROM members_wishlist WHERE member_id =  c.owner AND deck_id = c.deck) as wishlist_flag,
								EXISTS (SELECT 1 FROM cards WHERE owner = c.owner AND deck = c.deck AND number = c.number) as owned_flag
							FROM (SELECT  ".$this->deck." as deck, ".$this->number." as number, ".$compare_user_id." as owner) c ";
                        break;
            		default: // also works, but is usually slower
	            		$sql_status_joins = '';
	            		$sql_status_flag_fields = '';
	            		foreach($card_stati as $nt_status){
	            			if(!$nt_status->isNew()){
	            				$sql_status_flag_fields.= "status" . $nt_status->getId() . ".flag as status" . $nt_status->getId() . "_flag, ";
	            				$sql_status_flag_fields.= "in_status" . $nt_status->getId() . ".flag as in_status" . $nt_status->getId() . "_flag, ";
	            				$sql_status_joins.= "
		                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = " . $nt_status->getId() . " )
		                                status" . $nt_status->getId() . " ON status" . $nt_status->getId() . ".deck = c.deck
		                            LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id = " . $nt_status->getId() . " )
		                                in_status" . $nt_status->getId() . " ON in_status" . $nt_status->getId() . ".deck = c.deck AND in_status" . $nt_status->getId() . ".number = c.number ";
	            			}
	            		}
                        $sql = "SELECT 
									$sql_status_flag_fields wishlist.flag as wishlist_flag, mastered.flag as mastered_flag, owned.flag as owned_flag, 
									not_tradeable.flag as not_tradeable_flag, in_not_tradeable.in_not_tradeable_flag
								FROM (SELECT  ".$this->deck." as deck, ".$this->number." as number) c 
								$sql_status_joins
								LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$compare_user_id.") 
									mastered ON mastered.deck = c.deck 
								LEFT JOIN (SELECT DISTINCT deck_id, 1 as flag FROM members_wishlist WHERE member_id = ".$compare_user_id.") 
									wishlist ON wishlist.deck_id = c.deck 
								LEFT JOIN (SELECT DISTINCT deck, number, 1 as flag FROM cards WHERE owner = ".$compare_user_id.") 
									owned ON owned.deck = c.deck AND owned.number = c.number 
								LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str) )
	                                not_tradeable ON not_tradeable.deck = c.deck
								LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$compare_user_id." AND status_id IN($status_ids_not_tradeable_str) )
	                                in_not_tradeable ON in_not_tradeable.deck = c.deck AND in_not_tradeable.number = c.number";
                        break;
                }
            
            $req = $this->db->query($sql);
            $card_values = $req->fetch(PDO::FETCH_ASSOC);
            if($card_values !== false){
                $this->setPropValues($card_values);
                //flags
                foreach($card_stati as $nt_status){
                	// do not flag for status new
                	if(!$nt_status->isNew()){
                		if(isset($card_values['status'.$nt_status->getId().'_flag']) AND $card_values['status'.$nt_status->getId().'_flag'] == 1){                			
                			// in case current status is for collections
                			if($nt_status->isCollections()){
                				$this->setPropValues(['collect_flag'=>1]);
                			}else{
                				$this->setCustomFlagIds($nt_status->getId());
                			}
                		}
                		if(isset($card_values['in_status'.$nt_status->getId().'_flag']) AND $card_values['in_status'.$nt_status->getId().'_flag'] == 1){
                			// in case current status is for collections
                			if($nt_status->isCollections()){
                				$this->setPropValues(['in_collect_flag'=>1]);
                			}else{
                				$this->setCustomInFlagIds($nt_status->getId());
                			}
                		}
                	}
                }
            }
            
            
        }else{
            // set all flags to 0;
            $this->wishlist_flag = 0;
            $this->mastered_flag = 0;
            $this->owned_flag = 0;
            $this->collect_flag = 0;
            $this->in_collect_flag = 0;
            $this->not_tradeable_flag = 0;
            $this->in_not_tradeable_flag = 0;
        }
        return $this;
    }
    
    
    public function getPossessionCounter(){
        return $this->possession_counter;
    }
    
    public function getSortingOptions(){
        $options = array();
        
        $option_selected = false;
        // go throug statis (from highest to lowest priority)
        foreach(array_reverse(self::getAcceptedStatiObj()) as $status){
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
            		if(!$status->isCollections() AND !$status->isTradeable() AND $this->missingIn($status->getId()) AND ($this->getStatus()->isNew() OR $this->isTradeable()) ){
            			$option_selected = true;
            			$option['prop_selected'] = true;
            		}
            		// trade might - TRADEABLES
            		// select status is not collections and tradeable and at least one card of deck is in status
            		if(!$status->isCollections() AND $status->isTradeable() AND $this->deckInStatus($status->getId()) AND ($this->getStatus()->isNew() OR !$this->deckInNotTradeable()) ){
            			$option_selected = true;
            			$option['prop_selected'] = true;
            		}
            	}
            	
            	// add to option element to options array
            	$options[] = $option;
            }
        }
        // no other option fits and deck is mastered pre select lowest priority category
        if($option_selected AND $this->mastered() ){
        	$options[(count($options)-1)]['prop_selected'] = true;
        }
        // insert empty option
        $options[] = array('name'=>'','value'=>'','prop_selected'=>false);
        
        return array_reverse($options);
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
    
    
    public function getFlags() {
    	$flags = array();
    	foreach($this->custom_flags as $name => $value){
    		if($value == 1){
    			$flags[$name] = $value;
    		}
    	}
    	$flags['not_tradeable_flag'] = $this->not_tradeable_flag;
    	$flags['collect_flag'] = $this->collect_flag;
    	$flags['in_not_tradeable_flag'] = $this->in_not_tradeable_flag;
    	$flags['in_collect_flag'] = $this->in_collect_flag;
    	$flags['wishlist_flag'] = $this->wishlist_flag;
    	$flags['mastered_flag'] = $this->mastered_flag;
    	$flags['owned_flag'] = $this->owned_flag;
    	
    	return $flags;
    }
    
    
}