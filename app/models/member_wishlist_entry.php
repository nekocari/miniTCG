<?php
/**
 * 
 * Represents a Member Wishlist Entry
 * 
 * @author NekoCari
 *
 */

class MemberWishlistEntry extends DbRecordModel {
    
    protected $id, $member_id, $deck_id, $date, $utc;
    private $member_obj, $deck_obj;
    
    protected static
        $db_table = 'members_wishlist',
        $db_pk = 'id',
        $db_fields = array('id','member_id','deck_id','date','utc'),
        $sql_order_by_allowed_values = array('id','date','utc');
    

    /**
     * @param int $id
     * @return MemberWishlistEntry|NULL
     */
	public static function getById($id){
		return parent::getByPk($id);
	}
	
	/**
	 * @param int $id 
	 * @return MemberWishlistEntry[]
	 */
	public static function getByMemberId($id) {
		return parent::getWhere(['member_id'=>$id]);
	}
	
	/**
	 * @param int $id
	 * @return MemberWishlistEntry[]
	 */
	public static function getByDeckId($id) {
		return parent::getWhere(['deck_id'=>$id]);
	}
	
	public static function add($login,$deck){
		if($login instanceof Login AND $deck instanceof Carddeck){
			$entry = new MemberWishlistEntry();
			$entry->setPropValues(['member_id'=>$login->getUserId(),'deck_id'=>$deck->getId()]);
			if($entry->create()){
				return true;
			}
		}
		return false;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getMemberId() {
		return $this->member_id;
	}
	
	public function getDeckId() {
		return $this->deck_id;
	}
	
	public function getMember() {
    	if(!$this->member_obj instanceof Member){
        	$this->member_obj = Member::getById($this->member_id);
        }
       	return $this->member_obj;
	}
    
    public function getDeck() {
        if(!$this->deck_obj instanceof Carddeck){
            $this->deck_obj = Carddeck::getById($this->deck_id);
        }
        return $this->deck_obj;
    }
    
    public function getDate() {
    	$date = new DateTime($this->date);
    	return $date->format('d.m.Y');
    }
    
    
}