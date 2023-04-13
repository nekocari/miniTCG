<?php
/*
 * Manager Class for basic Routing
 */
class RoutesDb {
    
	private $db;
	private static $instance;
    private static $routes = array();
    private static $routes_grouped = array(); 
    private static $accepted_methods = array('get','post');
    private $uri_vars = array();
    
    private function __construct($db) {
    	if($db instanceof PDO){    		
    		$this->db = $db;
    		$this->loadRoutes();
    		return $this;
    	}else{
    		throw new ErrorException('parameter needs to be a valid Db connection object');
    	}
    }
    
    private function loadRoutes() {
    	$req = $this->db->query('SELECT * FROM routing');
    	if($req->rowCount()){
    		foreach($req->fetchAll(PDO::FETCH_ASSOC) as $route){
    			self::addRoute($route['identifier'], $route['url'], $route['controller'], $route['action'], $route['method']);
    		}
    	}
    }
    
    public static function getInstance($db){
    	if(is_null(self::$instance)){
    		self::$instance = new RoutesDb($db);
    	}
    	return self::$instance;
    }
    
    /**
     * adds a new route to routing
     * 
     * @param string $identifier 
     * @param string $uri
     * @param string $controller_name
     * @param string $action
     * @return boolean
     */
    public static function addRoute($identifier, $uri ,$controller_name, $action, $method = 'get') {
        $methods = explode('|',$method);
        foreach($methods as $method){
            if(in_array($method, self::$accepted_methods)){
                self::$routes[$identifier] = array('uri'=>$uri, 'controller'=>$controller_name, 'action'=>$action, 'method'=>$method);
            }
            self::$routes_grouped[$method][] = $identifier;
        }
    }
    
    /**
     * get controller name by identifier
     *
     * @param string $identifier
     *
     * @return string|boolean
     */
    public static function getUri($identifier) {
        if(key_exists($identifier, self::$routes)){
            return self::$routes[$identifier]['uri'];
        }else{
            return false;
        }
    }
    
    /**
     * get controller name by identifier
     *
     * @param string $identifier
     *
     * @return string|boolean
     */
    public static function getController($identifier) {
        if(key_exists($identifier, self::$routes)){
            return self::$routes[$identifier]['controller'];
        }else{
            return false;
        }
    }
    
    /**
     * get action name by identifier
     * 
     * @param string $identifier
     * @return string|boolean
     */
    public static function getAction($identifier) {
        if(key_exists($identifier, self::$routes)){
            return self::$routes[$identifier]['action'];
        }else{
            return false;
        }
    }
    
    /**
     * get the route key using the uri 
     * 
     * @param string $uri
     * 
     * @return string|boolean returns the identifier (key) of array or false if no matches are found
     */
    public function getRouteKeyByUri($uri) {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        foreach(self::$routes_grouped[$method] as $identifier) {
        	//if(self::$routes[$identifier]['uri'] == $uri) return $identifier;
        	if(preg_match('#^'.self::$routes[$identifier]['uri'].'$#',$uri,$vars)){
        		array_shift($vars); // removes the path from array
        		$this->uri_vars = $vars; // adds var values to class instance
        		return $identifier;
        	}
        }
        return false;
    }
    
    public function getVars(){
    	return $this->uri_vars;
    }
    
}
?>