<?php
/**
 * AppController
 * 
 * @author NekoCari
 */

require_once PATH.'core/dbconnect.php';
require_once PATH.'core/layout.php';
require_once PATH.'core/system_message.php';
require_once PATH.'core/login.php';
require_once PATH.'core/authorization.php';

class AppController {
    
    protected static $output_header_types = ['html','json','image/gif','image/png','image/jpeg'];
    protected $layout, $login, $auth, $db;
    
    public function __construct() {
    	MemberOnline::truncate();
        $this->login = new Login();
        $this->layout = new Layout($this->login);
        $this->auth = new Authorization($this->login);
        //$this->layout->addJsFile('jquery-3.5.1.min.js');
        //$this->layout->addJsFile('jquery-ui.min.js');
        //$this->layout->addJsFile('bootstrap.bundle.min.js');
        //$this->layout->addJsFile('utilities.'.JS_FINGERPRINT_UTILITIES.'.js');
        $this->db = Db::getInstance();
    }
    
    protected function layout(){
        return $this->layout;
    }
    
    /**
     * 
     * @return Login
     */
    protected function login(){
        return $this->login;
    }
    
    /**
     * 
     * @return PDO
     */
    protected function db() {
        return $this->db;
    }
    
    /**
     * 
     * @return Authorization
     */
    protected function auth() {
        return $this->auth;
    }
    
    /**
     * redirects to 404 page
     */
    protected function redirectNotFound(){
        header('Location: '.BASE_URI.Routes::getUri('not_found'));
    }
    
    /** 
     * redirects to login error page
     */
    protected function redirectNotLoggedIn(){
        if(!$this->login->isloggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin').'?rd=true');
        }
    }
    
    /**
     * redirects to authorization error page
     */
    protected function redirectNotAuthorized(){
        if(!$this->auth()->isAuthorized()){
            header("Location: ".BASE_URI.Routes::getUri('access_denied_authorization'));
        }
    }
    
    /**
     * set an output header based on type
     * @param string $type
     */
    protected function outputTypeHeader($type){
        if(in_array($type,self::$output_header_types)){
            switch($type){
                case 'json':
                    header('Content-type: application/json');
                    break;
                case 'image/gif':
                	header("content-type: image/gif");
                	break;
                case 'image/png':
                	header("content-type: image/png");
                	break;
                case 'image/jpeg':
                case 'image/jpg':
                	header("content-type: image/jpeg");
                	break;
                case 'html':
                    break;
            }
        }else{
            throw new ErrorException("$type is not a supported header type");
        }
    }
    
}

?>