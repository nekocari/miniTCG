<?php
/**
 *
 * Represents a List of Objects 
 *
 * @author NekoCari
 *
 */

abstract class Listable {
    
    protected $current_page = 1, $items_per_page = 25, $order_settings, $list_items, $pagination, $count, $search_str;
    
    protected static 
        $order_by_allowed_values = array(),
        $sql_direction_allowed_values = array('DESC','ASC');
    
        
    public function __construct(){
    }
    
    public abstract function getCount();
    public abstract function getItems();    
        
    public function getPage(){
        return $this->current_page;
    }
    
    public function getItemsPerPage(){
        return $this->items_per_page;
    }
    
    /**
     * @return mixed[] (key = field, value = direction)
     */
    public function getOrder(){
        return $this->order_settings;
    }
    
    public function getLimitArr(){
    	return [$this->getPagination()->getLimitStart(),$this->getPagination()->getItemsPerPage()];
    }
    
    public function getSearchStr(){
        return $this->search_str;
    }
    
    public function getPagination(){
        if(is_null($this->pagination)){
            $this->pagination = new MyPagination($this->getCount());
            $this->pagination->setPage($this->getPage());
            $this->pagination->setItemsPerPage($this->getItemsPerPage());
        }
        return $this->pagination;
    }
    
    /**
     * 
     * @param int $page
     * @return boolean
     */
    public function setPage($page){
        if(is_numeric($page)){
            $this->current_page = intval($page);
            return true;
        }else{
            return false;
        }
    }
    /**
     * 
     * @param int $per_page
     * @return boolean
     */
    public function setItemsPerPage($per_page){
        if(is_numeric($per_page)){
            $this->items_per_page = intval($per_page);
            return true;
        }else{
            return false;
        }
    }
    
    public function setOrder($order_settings){
        $this->order_settings = null;
        foreach($order_settings as $field => $direction){
            if(in_array($field, static::$order_by_allowed_values) AND in_array($direction, static::$sql_direction_allowed_values)){
                $this->order_settings[$field] = $direction;
            }else{
                throw new Exception($field.'=>'.$direction.' is invalid');
            }
        }
    }
    
    public function setSearchStr($search_str){
        $this->search_str = $search_str;
    }     
    
}