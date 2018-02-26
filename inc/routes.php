<?php
/*
 * Manager Class for basic Routing
 */
class Routes {
    
    private static $routes = array();
    
    /**
     * adds a new route to routing
     * 
     * @param string $identifier 
     * @param string $uri
     * @param string $controller_name
     * @param string $action
     */
    public static function addRoute($identifier, $uri ,$controller_name, $action) {
        self::$routes[$identifier] = array('uri'=>$uri, 'controller'=>$controller_name, 'action'=>$action);
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
    public static function getRouteKeyByUri($uri) {
        foreach(self::$routes as $identifier => $route) {
            if($route['uri'] == $uri) return $identifier;
        }
        return false;
    }
    
}
?>