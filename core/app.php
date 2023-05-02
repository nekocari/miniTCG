<?php
require_once 'config/constants.php';
require_once 'config/system_messages.php';
require_once 'core/dbconnect.php';
require_once 'core/routes_db.php';


class App {
	
	private $controller, $action, $identifier, $routes, $vars, $controller_obj;
	
	public function __construct(){
		try{
			$this->routes = Routes::getInstance(Db::getInstance());
			$this->autoloading();
		}
		catch(PDOException $e){
			die("mini TCG setup not complete");
		}
	}
	
    
    public function init() {
    	session_start();
        if(isset($_GET['uri'])) {
        	$this->parseUri($_GET['uri']);
        }else{
        	$this->controller =   Routes::getController('homepage');
        	$this->action =       Routes::getAction('homepage');
        }
        
        $this->display();     
    }
    
    private function camelToSnake($text){
    	return strtolower(substr(preg_replace('/[A-Z]{1}/','_\0', $text),1));
    }
    
    private function snakeToCamel($text){
    	return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $text))));
    }
    
    private function parseUri($uri){
    	if(($this->identifier = $this->routes->getRouteKeyByUri($uri)) != false){ 
    		$this->controller =   Routes::getController($this->identifier);
    		$this->action =       Routes::getAction($this->identifier);
    	}else{
    		/* do NOT use in production - JUST TO TEST
    		$uri_parts = explode('/', $uri);
    		if(count($uri_parts) == 2){
    			$this->controller = $uri_parts[0];
    			$this->action = str_replace(['.php'],[''],$uri_parts[1]);
    		} */
    	}
    	if(!$this->controller OR !$this->action){ 
    		header('Location: '.BASE_URI.Routes::getUri('not_found')); 
    	}
    }
    
    private function autoloading(){
    	spl_autoload_register(function ($class_name) {
    		$sources = array('app/models/','helper/','app/controllers/');
    		foreach($sources as $source){
    			if($source != 'app/controllers/' OR $class_name == 'AppController'){
    				// not controller class
    				$file = PATH.$source.$this->camelToSnake($class_name).'.php';
    			}else{
    				// controller class!
    				$file = PATH.$source.$this->camelToSnake(str_replace('Controller','',$class_name)).'.php';
    			}
    			if(file_exists($file)){
    				require_once($file);
    				return;
    			}
    		}
    	});
    }
    
    private function display(){    	
    	$controller_filename = $this->camelToSnake($this->controller);
    	$controller_path = PATH.'app/controllers/'.$controller_filename.'.php';
    	if(file_exists($controller_path)) {
    		require_once $controller_path;
    		$classname = $this->controller."Controller";
    		$this->controller_obj = new $classname();
    		
    		if(method_exists($this->controller_obj, $this->action)){
    			call_user_func_array(array($this->controller_obj,$this->action), $this->routes->getVars());
    		}else{
    			die('action '.$this->action.' not found in controller '.$this->controller);
    			header('Location: '.BASE_URI.Routes::getUri('not_found'));
    		}
    	}else{
    		die('controller '.$this->controller.' not found');
    		header('Location: '.BASE_URI.Routes::getUri('not_found'));
    	}
    }
    
}

?>