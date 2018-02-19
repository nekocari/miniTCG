<?php
/*
 * Controller for plain html pages
 */
class PagesController {
    
    public function home() {
        Layout::render('pages/home.php');
    }
    
    public function notfound() {
        Layout::render('templates/error.php');
    }
    
}