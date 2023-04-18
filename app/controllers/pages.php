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
    
    public function notFound() {
        $this->layout()->render('templates/error.php');
    }
    
    public function notLoggedIn() {
    	$this->layout()->render('templates/error_login.php');
    }
    
    public function accessDenied() {
    	$this->layout()->render('templates/error_rights.php');
    }
    
    public function faq() {
        $this->layout()->render('pages/faq.php');
    }
    
    public function imprint() {
        $this->layout()->render('pages/imprint.php');
    }
    
    /* use this as a template to add your own pages
     * - change "action" to be a unique method name within this class file
     * - set the path ("folder_within_views/your_page.php") 
     *   so it points to your file within the views folder
     * - don't forget to add a new route in routing.php in your root folder!
     */ 
    public function action() {
        $this->layout()->render('folder_within_views/your_page.php');
    }
    
}