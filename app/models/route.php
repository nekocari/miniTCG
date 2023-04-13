<?php
/**
 * 
 * Represents a Route Entry
 * 
 * @author NekoCari
 *
 */

class Route extends DbRecordModel{
    
    protected $identifier, $url, $controller, $action, $method, $deletable;
    
    protected static
        $db_table = 'routing',
        $db_pk = 'identifier',
        $db_fields = array('identifier','url','controller','action','method','deletable'),
        $sql_order_by_allowed_values = array('identifier','url','controller','method'),
    	$accepted_methods = array('get','post','get|post');
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @param string $id identifier string
     * @return get_called_class()|NULL
     */
    public static function getByIdentifier($id) {
        return parent::getByPk($id);
    }
    
    public function getIdentifier() {
    	return $this->identifier;
    }
    
    public function getUrl() {
    	return $this->url;
    }
    
    public function getController() {
    	return $this->controller;
    }
    
    public function getAction() {
    	return $this->action;
    }
    
    public function getMethod() {
    	return $this->method;
    }
    
    public function isDeletable() {
    	return boolval($this->deletable);
    }
    
    public function update() {
    	if($this->validate()){
	    	return parent::update();
    	}
    }
    	
    private function validate() {
    	// method is valid?
    	if(!in_array($this->getMethod(),self::$accepted_methods)){
    		throw new ErrorException('method '.$this->getMethod().' is not valid for route');
    	}
    	// controller exists?    	
    	$classname = $this->getController()."Controller";
    	$controller_obj = new $classname();
    	if(!$controller_obj instanceof $classname){
    		throw new ErrorException('Controller '.$this->getController().' was not found');
    	}
    	if(!method_exists($controller_obj, $this->getAction())){
    		throw new ErrorException('Action '.$this->getAction().' was not found');
    	}
    	return true;
    }
    
    
}