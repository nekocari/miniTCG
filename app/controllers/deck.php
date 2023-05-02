<?php
/*
 * Controller for deck related pages
 * 
 * @author NekoCari
 */

class DeckController extends AppController{
    
    /**
     * Decks Index
     */
    public function index() {
        
        if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        $decks = Carddeck::getAllByStatus('public',['deckname'=>'ASC']);
        $pagination = new Pagination($decks, 10, $currPage, Routes::getUri('deck_index'));
        
        $data = array();
        $data['decks'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        $this->layout()->render('deck/list.php', $data);
    }
    
    /**
     * Decks by Category
     */
    public function category() {
        
    	if(isset($_GET['id']) AND ($category = Category::getById($_GET['id'])) instanceof Category){
            
             $this->layout()->render('deck/category.php', ['category'=>$category]);
                
        }else{
            
            $this->redirectNotFound();
            
        }
        
    }
    
    /**
     * Individual Deck Page
     */
    public function deckpage() {
    	
    	if(Setting::getByName('cards_decks_public')->getValue() != 1){
    		$this->redirectNotLoggedIn();
    	}
        
        if(isset($_GET['id'])){
            
            $data = array();
            $data['deck'] = Carddeck::getById($_GET['id']);
            
            if($data['deck'] instanceof Carddeck){
                
                $this->layout()->render('deck/deckpage.php',$data);
                
            }
            
        }else{
        	$this->redirectNotFound();
        }
        
    }
    
    
    /**
     * Deck Upload
     */
    public function deckUpload() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->redirectNotAuthorized();
            
        $data['categories'] = Category::getALL();
        $data['deck_types'] = DeckType::getAll(['name'=>'ASC']); 
        $data['max_deck_size'] = DeckType::getMaxSize();
        
        if(isset($_POST['upload']) AND isset($_POST['name'],$_POST['deckname'],$_POST['subcategory'],$_POST['type']) AND isset($_FILES)){
            try{
                $upload = new CardUpload($_POST['name'], $_POST['deckname'], $_FILES, $this->login()->getUserId(), $_POST['subcategory'], $_POST['type'], $_POST['description']);
                
                if(($upload_status = $upload->store()) === true){
                    
                    $this->layout()->addSystemMessage('success','deck_upload_success');
                    
                }else{
                
                    $this->layout()->addSystemMessage('error','deck_upload_failed',[],' - '.$upload_status);
                    
                }
            }
            catch(PDOException $e){
                switch($e->getCode()){
                	case 23000:
                		$this->layout()->addSystemMessage('error','admin_upload_duplicate_key');
                        break;
                	default:
                		$this->layout()->addSystemMessage('error',9999,[],'<br>'.$e->getMessage());
                        break;
                }
            }
            catch(Exception $e){
            	$this->layout()->addSystemMessage('error','0',[],'<br>'.$e->getMessage());
            }
            
        }
        
        $this->layout()->render('admin/deck/upload.php', $data);
        
    }
    
    /**
     * Admin Deck List
     */
    public function adminDeckList() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->redirectNotAuthorized();
            
        $data['carddecks'] = Carddeck::getAll();
        $data['badge_css']['new'] = 'badge-primary';
        $data['badge_css']['public'] = 'badge-secondary';
        
        $this->layout()->render('admin/deck/list.php',$data);
            
    }
    
    /**
     * Deck edit
     */
    public function deckEdit() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->redirectNotAuthorized();
            
        if(isset($_POST['updateDeckdata'],$_POST['id'])){
            
            $deck = Carddeck::getById($_POST['id']);
            if($deck instanceof Carddeck){
                try{
                    $deck->setPropValues($_POST);
                    $deck->update();
                    if($deck->getSubcategory()->getId() != $_POST['subcategory']){
                        $relation = DeckSubcategory::getByUniqueKey('deck_id', $deck->getId());
                        $relation->setPropValues(['subcategory_id'=>$_POST['subcategory']]);
                        $relation->update();
                    }
                    
                    $this->layout()->addSystemMessage('success','deck_edit_success');
                }
                catch(Exception $e){
                	$this->layout()->addSystemMessage('error','unknown_error',[], $e->getMessage());
                }
                
            }
            
        }
        
        $data['categories'] = Category::getALL();            
        $data['deck_types'] = Carddeck::getAcceptedTypes(); 
        $data['deckdata'] = Carddeck::getById($_GET['id']);
        $data['memberlist'] = Member::getAll(['name'=>'ASC']);
        
        $this->layout()->render('admin/deck/edit.php',$data);
        
    }
    
}