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
    private $cards,$member, $member_id, $status, $status_id, $db, $query_style, $collections, $collection_decks;
    
    public function __construct($member,$query_style = 'join') {
    	$this->member = $member;
    	$this->member_id = $member->getId();
        $this->db = DB::getInstance();
        $this->setQueryStyle($query_style);
    }
    
    public static function getAcceptedStati(){
        return Card::getAcceptedStati();
    }
    
    public function setStatus($status) {
    	if($status instanceof CardStatus){
    		$this->status = $status;
    		$this->status_id = $status->getId();
    	}else{
    		throw new ErrorException('parameter 1 needs to be of type CardStatus');
    	}
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
    
    public function getCollectionCards() {
    	if(is_null($this->collections)){
	    	$coll_status = CardStatus::getCollect();
	    	$this->cards[$this->status_id] = Card::getWhere(['owner'=>$this->member_id,'status_id'=>$coll_status->getId()]);
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
    	
    	
    	//$status_id_new = CardStatus::getNew()->getId();
    	$status_id_collect = CardStatus::getCollect()->getId();
    	$status_ids_not_tradeable_str = 'NULL,';
    	$status_not_tradeable = CardStatus::getNotTradeable();
    	foreach($status_not_tradeable as $status_nt){
    		$status_ids_not_tradeable_str.= $status_nt->getId().',';
    	}
    	$status_ids_not_tradeable_str = substr($status_ids_not_tradeable_str,0,-1);
        
        // validate status
    	if(!array_key_exists($this->status_id, self::getAcceptedStati())){
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
        if(!isset($this->cards[$this->status_id][$trade_locked])){
        	$this->cards[$this->status_id][$trade_locked] = array();
            switch($this->query_style){
                case 'exists':
                    $sql = "SELECT c.*,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id = $status_id_collect and deck = c.deck) as collect_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id IN($status_ids_not_tradeable_str) and deck = c.deck) as keep_flag,
                        	EXISTS (SELECT 1 FROM decks_master WHERE member = c.owner and deck = c.deck) as mastered_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id = $status_id_collect and deck = c.deck AND number = c.number) as in_collect_flag,
                        	EXISTS (SELECT 1 FROM cards WHERE owner = c.owner and status_id IN($status_ids_not_tradeable_str) and deck = c.deck AND number = c.number) as in_keep_flag
                            FROM cards c $join_partial
                            WHERE c.owner = ".$this->member_id." AND c.status_id = '$this->status_id' $where_partial
                            ORDER BY name ASC";
                    break;
                default:
                	$sql = "SELECT c.*, collect.flag as collect_flag, keep.flag as keep_flag, mastered.flag as mastered_flag, in_keep.flag as in_keep_flag, in_collect.flag as in_collect_flag
                            FROM cards c $join_partial
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id = $status_id_collect)
                                collect ON collect.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id IN($status_ids_not_tradeable_str) )
                                keep ON keep.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, 1 as flag FROM decks_master WHERE member = ".$this->member_id.")
                                mastered ON mastered.deck = c.deck
                            LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id = $status_id_collect)
                                in_collect ON in_collect.deck = c.deck AND in_collect.number = c.number
                            LEFT JOIN (SELECT DISTINCT deck, number, status_id, 1 as flag FROM cards WHERE owner = ".$this->member_id." AND status_id IN($status_ids_not_tradeable_str) )
                                in_keep ON in_keep.deck = c.deck AND in_keep.number = c.number
                            WHERE c.owner = ".$this->member_id." AND c.status_id = '$this->status_id' $where_partial
                            ORDER BY name ASC";
                	break;
            }
            
            $req = $this->db->query($sql);
            
            foreach($req->fetchALL(PDO::FETCH_CLASS,'CardFlagged') as $card){
                $this->cards[$this->status_id][$trade_locked][] = $card;
            }
            
        }
        
        // return an array of card objects
        return $this->cards[$this->status_id][$trade_locked];
    }
    
    public function collectionView($deck_id) {
    	$deck_cards = $this->getCollectionCards()[$deck_id];
    	$deck = $this->getCollectionDecks()[$deck_id];
    	
    	if(count($deck_cards) AND $deck instanceof Carddeck){
    		if(empty($deck->getType()->getTemplatePath())){
    			$cards_per_row = $deck->getType()->getPerRow();
    			$deck_size = $deck->getType()->getSize();
	    		$view = '';
	    		for($i = 1; $i <= $deck_size; $i++){
	    			if(isset($deck_cards[$i])){
	    				$image = $deck_cards[$i]->getImageHtml();
	    			}else{
	    				if($deck->getType()->getFillerType() == 'identical'){
	    					// get general filler image;
	    					$image = 'X';
	    				}else{
	    					// get number specific filler image;
	    					$image = 'Y';
	    				}
	    			}
	    			$view.= $image;
	    			if($i % $cards_per_row == 0 AND $i != $deck_size){
	    				$view.= "<br>";
	    			}
	    		}
	    	}else{
	    		$template_path = PATH.'app/views/'.$this->getType()->getTemplatePath();
	    		if(file_exists($template_path)){
	    			$template = file_get_contents($template_path);
	    			$counter = 1;
	    			foreach($card_images as $image){
	    				$template = str_replace('['.$counter.']', $image, $template);
	    				$counter++;
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