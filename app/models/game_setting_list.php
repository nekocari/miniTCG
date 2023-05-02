<?php
/**
 *
 * Represents a List GameSetting of Objects 
 *
 * @author NekoCari
 *
 */

class GameSettingList extends Listable {
	
	protected 
		$order_settings = ['id'=>'ASC'],
		$items_per_page = 10;
	protected static $order_by_allowed_values = ['type','id'];
    
	public function getCount() {
		if(is_null($this->count)){
			if(!is_null($this->getSearchStr())){
				$query = "SELECT count(*) FROM ".GameSetting::getDbTableName()." WHERE name_json LIKE :param ";
				$req = Db::getInstance()->prepare($query);
				$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
				if($req->rowCount()){
					$this->count = $req->fetchColumn();
				}
			}else{
				$this->count = GameSetting::getCount();
			}
		}
		return $this->count;
	}
    
    
    public function getItems() {
    	if(is_null($this->list_items)){
    		if(!is_null($this->getSearchStr())){
    			$query = "SELECT * FROM ".GameSetting::getDbTableName()." WHERE name_json LIKE :param ";
    			$query.= GameSetting::buildSqlPart('order_by',$this->getOrder())['query_part'];
    			$query.= GameSetting::buildSqlPart('limit',$this->getLimitArr())['query_part'];
    			$req = Db::getInstance()->prepare($query);
    			$req->execute([':param'=>'%'.$this->getSearchStr().'%']);
    			if($req->rowCount()){
    				foreach($req->fetchAll(PDO::FETCH_CLASS, 'GameSetting') as $gs){
    					$this->list_items[] = $gs;
    				}
    			}
    		}else{
    			$this->list_items = GameSetting::getAll($this->getOrder(), $this->getLimitArr());
    		}
    	}
    	return $this->list_items;
    }
    
}