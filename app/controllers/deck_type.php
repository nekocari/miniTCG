<?php
/*
 * Controller for Deck Type related pages
 * 
 * @author NekoCari
 */

class DeckTypeController extends AppController {
	
	public function __construct() {
		parent::__construct();
		$this->auth()->setRequirements('roles', ['Admin']);
		$this->redirectNotAuthorized();
	}
    
    public function list() { 
    	$this->layout()->render('admin/deck_type/list.php',['deck_types'=>DeckType::getAll()]);
    }
    
    public function edit() {
    	if(isset($_GET['id']) AND ($deck_type = DeckType::getById($_GET['id'])) instanceof DeckType){
    		if(isset($_POST['updateDeckType'])){
    			try{
    				$deck_type->setPropValues($_POST);
    				$deck_type->update();
    				$this->layout()->addSystemMessage('success', 'changes_saved');
    			}
    			catch (Exception $e){
    				if($e->getCode() == 404){
    					$this->layout()->addSystemMessage('error', 'template_not_found', [], $e->getMessage());
    				}else{
    					$this->layout()->addSystemMessage('error', 'unknown_error', [], $e->getMessage());
    				}
    			}
    		}
        	$this->layout()->render('admin/deck_type/edit.php',['deck_type'=>$deck_type]);
    	}else{
    		$this->redirectNotFound();
    	}
    }
    
    public function add() {
    	$this->layout()->render('admin/deck_type/add.php');
    }
    
    public function delete() {
    	
    	$this->list();
    }
    
    
}