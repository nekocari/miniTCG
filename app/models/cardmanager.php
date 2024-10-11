<?php
/**
 * 
 * Represents a Cardmanager
 * 
 * @author NekoCari
 *
 */

class Cardmanager {
      
    private static $accepted_stati;
    private static $accepted_query_styles = array('join','exists');
    private $cards, $member, $member_id, $status, $status_id, $db, $query_style, $collections, $collection_decks, $viewer;
    
    public function __construct($member,$query_style = 'join') {
    	$this->member = $member;
    	$this->member_id = $member->getId();
        $this->db = DB::getInstance();
        $this->setQueryStyle($query_style);
    }
    
    public static function getAcceptedStati(){
        return Card::getAcceptedStati();
    }
    
    /**
     * @param CardStatus $status
     * @throws ErrorException
     * @return boolean
     */
    public function setStatus($status) {
    	if($status instanceof CardStatus){
    		$this->status = $status;
    		$this->status_id = $status->getId();
    		return true;
    	}else{
    		throw new ErrorException('parameter 1 needs to be of type CardStatus');
    	}
    	return false;
    }
    
    /**
     * @param Member $member
     * @return boolean
     */
    public function setViewer($member){
    	if($member instanceof Member){
    		$this->viewer = $member;
    		return true;
    	}else{
    		throw new ErrorException('parameter 1 needs to be of type Member');
    	}
    	return false;
    }
    
    public function setQueryStyle($style){
        if(in_array($style, self::$accepted_query_styles)){
            $this->query_style = $style;
        }
    }
    
    public function dissolveCollection($deck_id) {
    	$updated = true;
    	$coll_cards = Card::getWhere(['owner'=>$this->member_id,'deck'=>$deck_id]);
    	$new_stat = CardStatus::getNew();
    	foreach($coll_cards as $card){
    		$card->setStatusId($new_stat->getId());
    		if(!$card->update()){
    			$updated = false;
    		}
    	}
    	return $updated;
    }
    
    /**
     * 
     * @return Card[]
     */
    public function getCollectionCards() {
    	if(is_null($this->collections)){
	    	$coll_status = CardStatus::getCollect();
	    	$this->cards[$this->status_id] = Card::getWhere(['owner'=>$this->member_id,'status_id'=>$coll_status->getId()],['name'=>'ASC']);
	    	foreach($this->cards[$this->status_id] as $coll_card){
	    		if(!isset($this->collections[$coll_card->getDeckId()][$coll_card->getNumber()])){
	    			$this->collections[$coll_card->getDeckId()][$coll_card->getNumber()] = $coll_card;
	    		}
	    		$this->collection_decks[$coll_card->getDeckId()] = $coll_card->getDeck();
	    	}
    	}
    	return $this->collections;
    }
    
    public function getCollectionDecks(){
    	if(is_null($this->collections)){
    		$this->getCollectionCards();
    	}
    	return $this->collection_decks;
    }
    
    public function getStatus() {
    	return $this->status;
    }
    
    public function getCards($trade_locked=false) {
    	
    	// validate status
    	if(!array_key_exists($this->status_id, self::getAcceptedStati())){
    		throw new ErrorException('status is invalid');
    		return null;
    	}
    	
    	// unused --
    	//$status_id_new = CardStatus::getNew()->getId();
    	
    	// str with ids of stati not tradeable to use in sql in
    	$status_id_collect = CardStatus::getCollect()->getId();
    	$status_ids_not_tradeable_str = 'NULL,';
    	$status_not_tradeable = CardStatus::getNotTradeable();
    	foreach($status_not_tradeable as $status_nt){
    		$status_ids_not_tradeable_str.= $status_nt->getId().',';
    	}
    	$status_ids_not_tradeable_str = substr($status_ids_not_tradeable_str,0,-1);
    	
    	// str with ids of stati are tradeable to use in sql in
    	$status_ids_tradeable_str = 'NULL,';
    	$status_tradeable = CardStatus::getTradeable();
    	foreach($status_tradeable as $status_t){
    		$status_ids_tradeable_str.= $status_t->getId().',';
    	}
    	$status_ids_tradeable_str = substr($status_ids_tradeable_str,0,-1);
        
        // sql partial - differenciate between tradeable an non tradeable cards
        $join_partial = '';
        $where_partial = '';
        if(!$trade_locked){
            $join_partial = "LEFT JOIN trades t ON (t.offered_card = c.id OR t.requested_card = c.id) AND t.status = 'new'";
            $where_partial = "AND t.id IS NULL";
        }
        
        // check if array element does already exist
        // if not try to get the values from the Database
        if(!isset($this->cards[$this->status_id][$trade_locked])){
        	$this->cards[$this->status_id][$trade_locked] = array();
        	$card_stati_not_tradeable = CardStatus::getNotTradeable();
        	$card_stati = CardStatus::getAll();
            switch($this->query_style){
            	case 'exists':
            		$sql_not_tradeables = '';
            		foreach($card_stati_not_tradeable as $nt_status){
                		$sql_not_tradeables.= "
								EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id = " . $nt_status->getId() . " and deck = c.deck) as status" . $nt_status->getId() . "_flag, ";
                	}
                    $sql = "SELECT c.*,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id = $status_id_collect and deck = c.deck) as collect_flag,
							$sql_not_tradeables
                        	EXISTS (SELECT 1 FROM decks_master WHERE member = c.owner and deck = c.deck) as mastered_flag,
                        	EXISTS (SELECT 1 FROM members_wishlist WHERE member_id = c.owner and deck_id = c.deck) as wishlist_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id = $status_id_collect and deck = c.deck AND number = c.number) as in_collect_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_not_tradeable_flag
                            FROM cards c $join_partial
							LEFT JOIN decks d ON d.id = c.deck
                            WHERE c.owner = ".$this->member_id." AND c.status_id = '$this->status_id' $where_partial
							GROUP BY c.id
                            ORDER BY d.deckname ASC, c.number ASC";
                    break;
                default:
                	$sql_in_not_tradeables = '';
                	$sql_not_tradeables = '';
                	$sql_status_flag_fields = '';
                	foreach($card_stati as $nt_status){
                		if(!$nt_status->isNew()){
                		$sql_status_flag_fields.= "status" . $nt_status->getId() . ".flag as status" . $nt_status->getId() . "_flag, ";
                		$sql_status_flag_fields.= "in_status" . $nt_status->getId() . ".flag as in_status" . $nt_status->getId() . "_flag, ";
                		$sql_not_tradeables.= "
	                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id = " . $nt_status->getId() . " )
	                                status" . $nt_status->getId() . " ON status" . $nt_status->getId() . ".deck = c.deck";
                		$sql_in_not_tradeables.= "
	                            LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id = " . $nt_status->getId() . " )
	                                in_status" . $nt_status->getId() . " ON in_status" . $nt_status->getId() . ".deck = c.deck AND in_status" . $nt_status->getId() . ".number = c.number ";
                		}}
                	$sql = "SELECT c.*, 
								$sql_status_flag_fields 
								collect.flag as collect_flag, in_collect.flag as in_collect_flag,
								in_not_tradeable.flag as in_not_tradeable_flag, 
								wishlist.flag as wishlist_flag, mastered.flag as mastered_flag
                            FROM cards c $join_partial
							LEFT JOIN decks d ON d.id = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id = $status_id_collect)
                                collect ON collect.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id IN($status_ids_not_tradeable_str) )
                                not_tradeable ON not_tradeable.deck = c.deck
							$sql_not_tradeables
                            LEFT JOIN (SELECT DISTINCT deck_id, 1 as flag FROM members_wishlist WHERE member_id = ".$this->member_id.")
                                wishlist ON wishlist.deck_id = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$this->member_id.")
                                mastered ON mastered.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id = $status_id_collect)
                                in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                            LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id IN($status_ids_not_tradeable_str) )
                                in_not_tradeable ON in_not_tradeable.deck = c.deck AND in_not_tradeable.number = c.number
							$sql_in_not_tradeables
                            WHERE c.owner = ".$this->member_id." AND c.status_id = '$this->status_id' $where_partial
							GROUP BY c.id
                            ORDER BY d.deckname ASC, c.number ASC";
                	break;
            }
			
            $req = $this->db->query($sql);
            //echo $sql."<hr>";
            foreach($req->fetchALL(PDO::FETCH_ASSOC) as $card_values){
            	$card = new CardFlagged();
            	$card->setPropValues($card_values);
            	//flags
            	foreach($card_stati as $nt_status){
            		if(!$nt_status->isNew()){
	            		if(isset($card_values['status'.$nt_status->getId().'_flag']) AND $card_values['status'.$nt_status->getId().'_flag'] == 1){
	            			$card->setCustomFlagIds($nt_status->getId());
	            		}
	            		if(isset($card_values['in_status'.$nt_status->getId().'_flag']) AND $card_values['in_status'.$nt_status->getId().'_flag'] == 1){
	            			$card->setCustomInFlagIds($nt_status->getId());
	            		}
            		}
            	}
                $this->cards[$this->status_id][$trade_locked][] = $card;
            }
            
        }
        
        // return an array of card objects
        return $this->cards[$this->status_id][$trade_locked];
    }
    
    public function collectionView($deck_id,$search_link = false) {
    	$file_type = Setting::getByName('cards_file_type')->getValue();
    	$deck_cards = $this->getCollectionCards()[$deck_id];
    	$deck = $this->getCollectionDecks()[$deck_id];
    	$deck_card_width = $deck->getType()->getCardWidth(); 
    	$deck_card_height = $deck->getType()->getCardHeight(); 
    	$images = array();
    	
    	if(count($deck_cards) AND $deck instanceof Carddeck){
    		// create an array of images (html)
    		for($i = 1; $i <= $deck->getSize(); $i++){
    			
    			if(isset($deck_cards[$i])){ // card owned
    				$images[$i] = $deck_cards[$i]->getImageHtml();
    			}else{ // not owned
    				if($deck->getType()->getFillerType() == 'identical'){
    					if(!isset($filler_html)){
    						$filler_html = Card::getFillerHtml($deck->getType()->getFillerPath(), $deck_card_width, $deck_card_height);
    					}
    					// get general filler image;
    					$image = $filler_html;
    				}else{
    					// get number specific filler image;
    					$image = Card::getFillerHtml($deck->getType()->getFillerPath().$i.'.'.$file_type, $deck_card_width, $deck_card_height);;
    				}
    				if($search_link){
    					$image = '<a href="'.Routes::getUri('card_search').'?deck_id='.$deck->getId().'&number='.$i.'">'.$image.'</a>';
    				}
    				$images[$i] = $image;
    			}
    		}    		
    		
    		// put together view
    		if(empty($deck->getType()->getTemplatePath())){
    			// no specific template path was set - just display the images
    			$cards_per_row = $deck->getType()->getPerRow();
	    		$view = '';
	    		foreach ($images as $key => $image){
	    			$view.= $image;	    		
	    			if($key % $cards_per_row == 0 AND $key != $deck->getSize()){
	    				$view.= "<br>";
	    			}
	    		}
	    	}else{
	    		// load template file and replace placeholders with actual image html
	    		$template_path = DeckType::getTemplateBasePath(true).$deck->getType()->getTemplatePath();
	    		if(file_exists($template_path)){
	    			$template = file_get_contents($template_path);
	    			foreach ($images as $key => $image){
	    				$template = str_replace('['.$key.']', $image, $template);
	    			}
	    			$view = $template;
	    		}
	    	}
	    	return $view;
    	}else{
    		throw new ErrorException('deck_id not found');
    	}
    }
}