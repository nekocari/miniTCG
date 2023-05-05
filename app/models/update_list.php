<?php
/**
 *
 * Represents a List of Update Objects 
 *
 * @author NekoCari
 *
 */

class UpdateList extends Listable {
	
	protected 
		$order_settings = ['status'=>'ASC','date'=>'DESC'],
		$items_per_page = 10;
	protected static $order_by_allowed_values = ['status','id','date'];
    
	public function getCount() {
		if(is_null($this->count)){
			$this->count = Update::getCount();
		}
		return $this->count;
	}
    
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		$this->list_items = Update::getAll($this->getOrder(), $this->getLimitArr());
    	}
    	return $this->list_items;
    }
    
}