<?php
/**
 * Reflection Controller
 * 
 * @author NekoCari
 */


class ReflectionController extends AppController {
	
	public function __construct() {
		parent::__construct();
		$this->redirectNotLoggedIn();
	}
    
    /**
     * reference page for developers
     */
	public function overview() {
		$this->layout()->setMode('clear');
		// check if user has required rights
		$this->auth()->setRequirements('roles', ['Admin']);
		$this->redirectNotAuthorized();
		
    	$this->layout()->render('reflection/model_reference.php');
    }
}
?>