<?php
/**
 *
 * Represents a List Member of Objects 
 *
 * @author NekoCari
 *
 */

class MemberList extends Listable {
	
	protected $order_settings = ['id'=>'ASC'];
	protected static $order_by_allowed_values = ['name','id'];
	private $member_status;
    
	public function getCount() {
		$conditions = null;
		if(!is_null($this->member_status)){
			$conditions['status'] = $this->member_status;
		}
		$this->count = Member::getCount($conditions);
	}
    
    
    public function getItems() {
    	$conditions = null;
    	if(!is_null($this->member_status)){
    		$conditions['status'] = $this->member_status;
    	}
    	$this->list_items = Member::getWhere($conditions, $this->getOrder(), $this->getLimitArr());
    }
    
    public function setMemberStatus($status) {
    	$this->member_status = $status;
    }
    
}