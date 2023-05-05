<?php
/*
 * Controller for static html pages
 * 
 * @author NekoCari
 */

class PagesController extends AppController {
    
    public function home() { 
    	$this->layout()->render('pages/home.php');
    }
    
    public function faq() {
    	$this->layout()->render('pages/faq.php');
    }
    
    public function imprint() {
    	$this->layout()->render('pages/imprint.php');
    }
    
    public function notFound() {
        $this->layout()->render('templates/error.php');
    }
    
    public function notLoggedIn() {
    	$this->layout()->render('templates/error_login.php');
    }
    
    public function accessDenied() {
    	$this->layout()->render('templates/error_rights.php');
    }
    
    public function view($pagename){
    	$path = $this->layout()->getPartialPath('/pages/'.$pagename.'.php');
    	// exclude home, imprint and faq because they have their own specific route and action!
    	if(!in_array($pagename,['home','imprint','faq']) AND file_exists($path)){
    		$this->layout()->render('/pages/'.$pagename.'.php');
    	}else{
    		$this->redirectNotFound();
    	}
    }
    
    /* use this as a template to add your own pages
     * - change "action" to be a unique method name within this class file
     * - set the path ("folder_within_views/your_page.php") 
     *   so it points to your file within the views folder
     * - don't forget to add a new route!
     
    public function action() {
        $this->layout()->render('folder_within_views/your_page.php');
    }
    
    */ 
    
}