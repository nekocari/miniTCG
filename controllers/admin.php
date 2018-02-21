<?php
/*
 * Admin Controller
 */
class AdminController {
	
    /**
     * Team Dashboard 
     */
    public function dashboard() {
    	if(!isset($_SESSION['user'])){
    		header("Location: ".BASE_URI."signin.php");
    	}
    	require_once PATH.'models/admin.php';
    	$admin = new Admin(Db::getInstance(),$_SESSION['user']);
    	if($admin->getRights() != NULL){
    	    Layout::render('admin/dashboard.php');
    	}else{
    	    Layout::render('templates/error_rights.php');
    	}
    }
	
}
?>