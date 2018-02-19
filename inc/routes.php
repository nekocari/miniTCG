<?php
/*
 * Manager Class for basic Routing
 */
class Routes {
    
    private static $routes = array();
    
    /**
     * adds a new route to routing
     * 
     * @param string $uri
     * @param string $controller_name
     * @param string $action
     */
    public static function addRoute($uri ,$controller_name, $action) {
        self::$routes[$uri] = array('controller'=>$controller_name, 'action'=>$action);
    }
    
    /**
     * get controller name by uri
     * 
     * @param string $uri
     * 
     * @return string|boolean
     */
    public static function getController($uri) {
        if(key_exists($uri, self::$routes)){
            return self::$routes[$uri]['controller'];
        }else{
            return false;
        }
    }
    
    /**
     * get action name by uri
     * 
     * @param string $uri
     * @return string|boolean
     */
    public static function getAction($uri) {
        if(key_exists($uri, self::$routes)){
            return self::$routes[$uri]['action'];
        }else{
            return false;
        }
    }
    
}
?>