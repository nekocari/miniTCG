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
        $pagination = new Pagination($decks, 10, $currPage, RoutesDb::getUri('deck_index'));
        
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
    	
    	$this->redirectNotLoggedIn();
        
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
            	
    	$this->redirectNotLoggedIn();
        
        // check if user has required rights
        $user_rights = $this->login()->getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('CardCreator',$user_rights) ){
            die($this->layout()->render('templates/error_rights.php'));
        }
            
        $setting_decksize = Setting::getByName('cards_decksize');
        
        if($setting_decksize instanceof Setting){
            
            $data['settings']['decksize'] = $setting_decksize->getValue();
            
        }else{
            
            $data['_error'][] = $setting_decksize;
            
        }
        
        $data['categories'] = Category::getALL();
        
        $data['deck_types'] = Carddeck::getAcceptedTypes(); 
        
        if(isset($_POST['upload']) AND isset($_POST['name'],$_POST['deckname'],$_POST['subcategory'],$_POST['type']) AND isset($_FILES)){
            try{
                $upload = new CardUpload($_POST['name'], $_POST['deckname'], $_FILES, $_SESSION['user']->id, $_POST['subcategory'], $_POST['type'], $_POST['description']);
                
                if(($upload_status = $upload->store()) === true){
                    
                    $data['_success'][] = SystemMessages::getSystemMessageText('deck_upload_success');
                    
                }else{
                
                    $data['_error'][] = SystemMessages::getSystemMessageText('deck_upload_failed').' - '.$upload_status;
                    
                }
            }
            catch(PDOException $e){
                switch($e->getCode()){
                    case 23000: 
                        $error_text = SystemMessages::getSystemMessageText('admin_upload_duplicate_key');
                        break;
                    default: 
                        $error_text = SystemMessages::getSystemMessageText(9999).'<br>'.$e->getMessage();
                        break;
                }
                $data['_error'][] = $error_text;
            }
            catch(Exception $e){
                if($error_text = SystemMessages::getSystemMessageText($e->getMessage()) == 'TEXT MISSING'){
                    $error_text = $e->getMessage();
                }
                $data['_error'][] = $error_text;
            }
            
        }
        
        $this->layout()->render('admin/deck/upload.php', $data);
        
    }
    
    /**
     * Admin Deck List
     */
    public function adminDeckList() {
    	
    	$this->redirectNotLoggedIn();
        
        // check if user has required rights
        $user_rights = $this->login()->getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('CardCreator',$user_rights) ){
            die($this->layout()->render('templates/error_rights.php'));
        }
            
        $data['carddecks'] = Carddeck::getAll();
        $data['badge_css']['new'] = 'badge-primary';
        $data['badge_css']['public'] = 'badge-secondary';
        
        $this->layout()->render('admin/deck/list.php',$data);
            
    }
    
    /**
     * Deck edit
     */
    public function deckEdit() {
    	
    	$this->redirectNotLoggedIn();
        
        // check if user has required rights
        $user_rights = $this->login()->getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('CardCreator',$user_rights) ){
            die($this->layout()->render('templates/error_rights.php'));
        }
            
        if(isset($_POST['updateDeckdata'],$_POST['id'], $_POST['name'], $_POST['creator'], $_POST['type'])){
            
            $deck = Carddeck::getById($_POST['id']);
            if($deck instanceof Carddeck){
                try{
                    $prop_values = array(
                        'name'=>$_POST['name'],
                        'creator'=>$_POST['creator'],
                        'description'=>$_POST['description'],
                        'description_html'=>'',
                        'type'=>$_POST['type']
                    );
                    $deck->setPropValues($prop_values);
                    if($deck_updated = $deck->update()){
                    }
                    if($deck->getSubcategory()->getId() != $_POST['subcategory']){
                        $relation = DeckSubcategory::getByUniqueKey('deck_id', $deck->getId());
                        $relation->setPropValues(['subcategory_id'=>$_POST['subcategory']]);
                        $relation_updated = $relation->update();
                    }
                    
                    $data['_success'][] = SystemMessages::getSystemMessageText('deck_edit_success');
                }
                catch(Exception $e){
                    $data['_error'][] = $e->getMessage();
                }
                
            }
            
        }
        
        $data['categories'] = Category::getALL();            
        $data['deck_types'] = Carddeck::getAcceptedTypes(); 
        $data['deckdata'] = Carddeck::getById($_GET['id']);
        $data['card_images'] = $data['deckdata']->getImages();
        $data['memberlist'] = Member::getAll(['name'=>'ASC']);
        
        $this->layout()->render('admin/deck/edit.php',$data);
        
    }
    
}