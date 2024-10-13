<?php
/**
 *
 * Represents a Members Wishlist 
 *
 * @author NekoCari
 *
 */

class Wishlist extends Listable {
	
	protected 
		$items_per_page = 9999,
		$order_settings = ['id'=>'ASC'];
	protected static $order_by_allowed_values = ['deckname','id','date'];
	private $member_id, $member_obj, $viewer_id, $viewer_obj;
    
	
	public function __construct($member_id){
		$this->member_id = $member_id;
		parent::__construct();
	}
	
	public function getMemberId(){
		return $this->member_id;
	}
	
	public function getViewer(){
		return $this->viewer_obj;
	}
	
	public function getCount() {
		if(is_null($this->count)){
			$this->count = MemberWishlistEntry::getCount(['member_id'=>$this->getMemberId()]);
		}
		return $this->count;
	}
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		$sql = "SELECT w.* FROM " . MemberWishlistEntry::getDbTableName() . " w 
					JOIN " . Carddeck::getDbTableName() . " c ON c.id = w.deck_id
					WHERE w.member_id = " . intval($this->member_id) . " 
					ORDER BY c.deckname ASC";
    		$req = DB::getInstance()->query($sql);
    		$this->list_items = $req->fetchAll(PDO::FETCH_CLASS,"MemberWishlistEntry");
    	}
    	return $this->list_items;
    }
    
    public function setViewer($viewer_obj){
    	if(!$viewer_obj instanceof Member){
    		throw new ErrorException();
    	}
    	$this->viewer_obj;
    	return true;
    }
    
}