<?php
/**
 *
 * Represents a List Member of Objects 
 *
 * @author NekoCari
 *
 */

class MemberList extends Listable {
	
	protected 
		$items_per_page = 10,
		$order_settings = ['id'=>'ASC'];
	protected static $order_by_allowed_values = ['name','id'];
	private $member_status;
    
	public function getCount() {
		
		$conditions = array();
		if(!is_null($this->member_status)){
			$conditions['status'] = $this->member_status;
		}
		if(!is_null($this->getSearchStr())){
			$query = "SELECT count(*) FROM ".Member::getDbTableName()." WHERE name LIKE :param ";
			if(!is_null($this->member_status)){
				$query.= " AND status = '".$this->member_status."' ";
			}
			$req = Db::getInstance()->prepare($query);
			$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
			if($req->rowCount()){
				$this->count = $req->fetchColumn();
			}
		}elseif(count($conditions)){
			$this->count = Member::getCount($conditions);
		}else{
			$this->count = Member::getCount();
		}
		return $this->count;
	}
    
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		$this->list_items = array();
    		$conditions = array();
    		if(!is_null($this->member_status)){
    			$conditions['status'] = $this->member_status;
    		}
	    	if(!is_null($this->getSearchStr())){
	    		$query = "SELECT * FROM ".Member::getDbTableName()." WHERE name LIKE :param ";
	    		if(!is_null($this->member_status)){
	    			$query.= " AND status = '".$this->member_status."' ";
	    		}
	    		$query.= Member::buildSqlPart('order_by',$this->getOrder())['query_part'];
	    		$query.= Member::buildSqlPart('limit',$this->getLimitArr())['query_part'];
	    		$req = Db::getInstance()->prepare($query);
	    		$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
	    		if($req->rowCount()){
	    			foreach($req->fetchAll(PDO::FETCH_CLASS, 'Member') as $member){
	    				$this->list_items[] = $member;
	    			}
	    		}
	    	}elseif(count($conditions)){
	    		$this->list_items = Member::getWhere($conditions, $this->getOrder(), $this->getLimitArr());
	    	}else{
	    		$this->list_items = Member::getAll($this->getOrder(), $this->getLimitArr());
	    	}
    	}
    	return $this->list_items;
    }
    
    public function setMemberStatus($status) {
    	$this->member_status = $status;
    }
    
}