<?php
/**
 *
 * Represents a List of Route Objects 
 *
 * @author NekoCari
 *
 */

class RouteList extends Listable {
	
	protected 
		$order_settings = ['identifier'=>'ASC'],
		$items_per_page = 10;
	protected static $order_by_allowed_values = ['identifier','url'];
    
	public function getCount() {
		if(is_null($this->count)){
			if(!is_null($this->getSearchStr())){
				$query = "SELECT count(*) FROM ".Route::getDbTableName()." WHERE identifier LIKE :param OR url LIKE :param ";
				$req = Db::getInstance()->prepare($query);
				$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
				if($req->rowCount()){
					$this->count = $req->fetchColumn();
				}
			}else{
				$this->count = Route::getCount();
			}
		}
		return $this->count;
	}
    
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		if(!is_null($this->getSearchStr())){
    			$query = "SELECT * FROM ".Route::getDbTableName()." WHERE identifier LIKE :param OR url LIKE :param ";
    			$query.= Route::buildSqlPart('order_by',$this->getOrder())['query_part'];
    			$query.= Route::buildSqlPart('limit',$this->getLimitArr())['query_part'];
    			$req = Db::getInstance()->prepare($query);
    			$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
    			if($req->rowCount()){
    				foreach($req->fetchAll(PDO::FETCH_CLASS, 'Route') as $gs){
    					$this->list_items[] = $gs;
    				}
    			}
    		}else{
    			$this->list_items = Route::getAll($this->getOrder(), $this->getLimitArr());
    		}
    	}
    	return $this->list_items;
    }
    
}