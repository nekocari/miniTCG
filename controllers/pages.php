<?php
/*
 * Controller for static html pages
 * home is a bad exception because of the news ;) 
 * news will get their own controller in the future!
 */

require_once PATH.'models/news.php';

class PagesController {
    
    public function home() { 
        Layout::render('pages/home.php');
    }
    
    public function notFound() {
        Layout::render('templates/error.php');
    }
    
    public function notLoggedIn() {
        Layout::render('templates/error_login.php');
    }
    
    public function faq() {
        Layout::render('pages/faq.php');
    }
    
    public function imprint() {
        Layout::render('pages/imprint.php');
    }
    
    /* use this as a template to add your own pages
     * - change "action" to be a unique method name within this class file
     * - set the path ("folder_within_views/your_page.php") 
     *   so it points to your file within the views folder
     * - don't forget to add a new route in routing.php in your root folder!
     */ 
    public function action() {
        Layout::render('folder_within_views/your_page.php');
    }
    
}