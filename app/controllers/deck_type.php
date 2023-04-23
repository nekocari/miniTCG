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
		if(isset($_POST['action']) AND $_POST['action'] == 'del_deck_type' AND 
				isset($_POST['id']) AND ($deck_type = DeckType::getById($_POST['id'])) instanceof DeckType){
			if($deck_type->delete()){
				$this->layout()->addSystemMessage('success', 'changes_saved');
			}
		}
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
    	$deck_type = new DeckType();
    	if(isset($_POST['addDeckType'])){
	    	try{
	    		unset($_POST['id']);
	    		$deck_type->setPropValues($_POST);
	    		$deck_type->create();
	    		$this->layout()->addSystemMessage('success', 'changes_saved');
	    	}
	    	catch (Exception $e){
	    		if($e->getCode() == 404){
	    			$this->layout()->addSystemMessage('error', 'template_not_found', [], $e->getMessage());
	    		}else{
	    			$this->layout()->addSystemMessage('error', 'unknown_error', [], $e->getMessage());
	    		}
	    	}
	    	$this->layout()->render('admin/deck_type/edit.php',['deck_type'=>$deck_type]);
    	}else{
    		$this->layout()->render('admin/deck_type/add.php',['deck_type'=>$deck_type]);
    	}
    }
    
    public function editTemplate() {
    	if(isset($_GET['id']) AND ($deck_type = DeckType::getById($_GET['id'])) instanceof DeckType){
    		if(isset($_POST['saveTemplate'])){
    			try{
    				// file doesn't exists? -> create one
    				if(empty($deck_type->getTemplatePath()) AND !empty($_POST['template_content'])){
    					$new_file_name = 'deck_type_'.$deck_type->getId().'.php';
    					$new_file_path = $deck_type->getTemplateBasePath(true).$new_file_name;
    					if(!file_exists($new_file_path)){ 
    					$file = fopen($new_file_path, 'w');
    						fclose($file);
    					}
    					$deck_type->setPropValues(['template_path'=>$new_file_name]);
    					$deck_type->update();
    				}
    				file_put_contents(DeckType::getTemplateBasePath(true).$deck_type->getTemplatePath(), $_POST['template_content']);
    				$this->layout()->addSystemMessage('success', 'changes_saved');
    			}
    			catch (Exception $e){
    			}
    		}
    		$this->layout()->render('admin/deck_type/edit_template.php',['deck_type'=>$deck_type]);
    	}else{
    		$this->redirectNotFound();
    	}
    }
    
    
}