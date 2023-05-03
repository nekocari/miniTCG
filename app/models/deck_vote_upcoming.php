<?php
/**
 * 
 * Represents a vote for upcoming dekcs
 * 
 * @author NekoCari
 *
 */

class DeckVoteUpcoming extends DbRecordModel {
    
	protected $member_id, $deck_id, $date, $utc;
	private $member_obj, $deck_obj;
    
    
    // for active record
    protected static
    $db_table = 'decks_votes_upcoming',
    $db_pk = array('deck_id','member_id'),
    $db_fields = array('member_id','deck_id','date','utc'),
	$sql_order_by_allowed_values = array('member_id','deck_id','date','utc'),
 	$sql_direction_allowed_values = array('DESC','ASC');
   
    public function __construct() {
        parent::__construct();
    }
    
    /**
     *
     * @param int $member_id
     * @return DeckVoteUpcoming[]
     */
    public static function getByMemberId($member_id) {
    	return parent::getWhere(['member_id'=>$member_id]);
    }
    
    /**
     *
     * @param int $deck_id
     * @return DeckVoteUpcoming[]
     */
    public static function getByDeckId($deck_id) {
    	return parent::getWhere(['deck_id'=>$deck_id]);
    }
    
    public static function getVoteCountforDeck($deck_id){
    	return self::getCount(['deck_id'=>$deck_id]);
    }
    
    public static function getVote($member_id,$deck_id){
    	$vote = self::getWhere(['deck_id'=>$deck_id,'member_id'=>$member_id]);
    	if(is_array($vote) AND count($vote) == 1){
    		$vote = $vote[0];
    	}
    	return $vote;
    }
    
    public static function addVote($member,$deck){
    	if($member instanceof Member AND $deck instanceof Carddeck AND $deck->getStatus() == 'new'){
    		$vote = new DeckVoteUpcoming();
    		$vote->setPropValues(['member_id'=>$member->getId(), 'deck_id'=>$deck->getId()]);
    		$vote->create();
    		return true;
    	}
    	return false;
    }
    
    public function getMemberId() {
    	return $this->member_id;
    }
    
    public function getDeckId() {
    	return $this->deck_id;
    } 
    
    public function delete() {
    	$query = "DELETE FROM ".self::getDbTableName()." WHERE member_id = ".$this->getMemberId()." AND deck_id = ".$this->getDeckId();
    	$req = $this->db->query($query);
    	if($req->rowCount()){
    		return true;
    	}else{
    		return false;
    	}
    }
    

}