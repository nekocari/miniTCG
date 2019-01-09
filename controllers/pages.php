<?php
/*
 * Controller for static html pages
 */
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
    
}