<?php
/**
 *
 * Represents a List of Carddeck Objects 
 *
 * @author NekoCari
 *
 */

class CarddeckList extends Listable {
	
	protected 
		$items_per_page = 10,
		$order_settings = ['id'=>'ASC'];
	protected static $order_by_allowed_values = ['deckname','name','status','id'];
	private $deck_status;
    
	public function getCount() {
		
		$conditions = array();
		if(!is_null($this->deck_status)){
			$conditions['status'] = $this->deck_status;
		}
		if(!is_null($this->getSearchStr())){
			$query = "SELECT count(*) FROM ".Carddeck::getDbTableName()." WHERE (deckname LIKE :param OR name LIKE :param ) ";
			if(!is_null($this->deck_status)){
				$query.= " AND status = '".$this->deck_status."' ";
			}
			$req = Db::getInstance()->prepare($query);
			$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
			if($req->rowCount()){
				$this->count = $req->fetchColumn();
			}
		}elseif(count($conditions)){
			$this->count = Carddeck::getCount($conditions);
		}else{
			$this->count = Carddeck::getCount();
		}
		return $this->count;
	}
    
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		$this->list_items = array();
    		$conditions = array();
    		if(!is_null($this->deck_status)){
    			$conditions['status'] = $this->deck_status;
    		}
	    	if(!is_null($this->getSearchStr())){
	    		$query = "SELECT * FROM ".Carddeck::getDbTableName()." WHERE (deckname LIKE :param OR name LIKE :param ) ";
	    		if(!is_null($this->deck_status)){
	    			$query.= " AND status = '".$this->deck_status."' ";
	    		}
	    		$query.= Carddeck::buildSqlPart('order_by',$this->getOrder())['query_part'];
	    		$query.= Carddeck::buildSqlPart('limit',$this->getLimitArr())['query_part'];
	    		$req = Db::getInstance()->prepare($query);
	    		$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
	    		if($req->rowCount()){
	    			foreach($req->fetchAll(PDO::FETCH_CLASS, 'Carddeck') as $deck){
	    				$this->list_items[] = $deck;
	    			}
	    		}
	    	}elseif(count($conditions)){
	    		$this->list_items = Carddeck::getWhere($conditions, $this->getOrder(), $this->getLimitArr());
	    	}else{
	    		$this->list_items = Carddeck::getAll($this->getOrder(), $this->getLimitArr());
	    	}
    	}
    	return $this->list_items;
    }
    
    public function getDeckStatus() {
    	return $this->deck_status;
    }
    
    public function setDeckStatus($status) {
    	$this->deck_status = $status;
    }
    
}