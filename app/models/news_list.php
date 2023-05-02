<?php
/**
 *
 * Represents a List of News Objects 
 *
 * @author NekoCari
 *
 */

class NewsList extends Listable {
	
	protected 
		$order_settings = ['utc'=>'DESC'],
		$items_per_page = 10;
	protected static $order_by_allowed_values = ['utc','id'];
    
	public function getCount() {
		if(is_null($this->count)){
			if(!is_null($this->getSearchStr())){
				$query = "SELECT count(*) FROM ".News::getDbTableName()." WHERE title LIKE :param ";
				$req = Db::getInstance()->prepare($query);
				$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
				if($req->rowCount()){
					$this->count = $req->fetchColumn();
				}
			}else{
				$this->count = News::getCount();
			}
		}
		return $this->count;
	}
    
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		if(!is_null($this->getSearchStr())){
    			$query = "SELECT * FROM ".News::getDbTableName()." WHERE title LIKE :param  OR text LIKE :param ";
    			$query.= News::buildSqlPart('order_by',$this->getOrder())['query_part'];
    			$query.= News::buildSqlPart('limit',$this->getLimitArr())['query_part'];
    			$req = Db::getInstance()->prepare($query);
    			$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
    			if($req->rowCount()){
    				foreach($req->fetchAll(PDO::FETCH_CLASS, 'News') as $news){
    					$this->list_items[] = $news;
    				}
    			}
    		}else{
    			$this->list_items = News::getAll($this->getOrder(), $this->getLimitArr());
    		}
    	}
    	return $this->list_items;
    }
    
}