<?php
/*
 * Controller for plain html pages
 */
class PagesController {
    
    public function home() {
        require_once('views/pages/home.php');
    }
    
}