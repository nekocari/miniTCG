<?php
/*
 * Controller for deck related pages
 */

require_once 'models/member.php';
require_once 'models/category.php';
require_once 'models/subcategory.php';
require_once 'models/carddeck.php';
require_once 'models/setting.php';
require_once 'models/admin.php';
require_once 'helper/pagination.php';
require_once 'helper/cardupload.php';

class DeckController {
    
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
        
        Layout::render('deck/list.php', $data);
    }
    
    /**
     * Decks by Category
     */
    public function category() {
        
        if(isset($_GET['id'])){
            
            try {    
                $data = array();
                $data['category'] = Category::getById($_GET['id']);
                $data['subcategories'] = Subcategory::getByCategory($_GET['id']);
                
                foreach($data['subcategories'] as $subcategory){
                    
                    $sub_id = $subcategory->getId();
                    $data['decks'][$sub_id] = Carddeck::getBySubcategory($sub_id);
                    
                }
                
                Layout::render('deck/category.php', $data);
                
            }
            
            catch (Exception $e){
                
                $data['_error'][] = $e->getMessage();
                Layout::render('error.php', $data);
                
            }
            
        }else{
            
            header("Location: ".BASE_URI.Routes::getUri('not_found'));
            
        }
        
    }
    
    /**
     * Individual Deck Page
     */
    public function deckpage() {
        
        if(isset($_SESSION['user']) AND isset($_GET['id'])){
            
            $data = array();
            $data['deck'] = Carddeck::getById($_GET['id']);
            
            if($data['deck'] instanceof Carddeck){
                
                Layout::render('deck/deckpage.php',$data);
                
            }else{
                
                header("Location: ".BASE_URI.Routes::getUri('not_found'));
                
            }
            
        }else{
            
            Layout::render('templates/error_login.php');
            
        }
        
    }
    
    
    /**
     * Deck Upload
     */
    public function deckUpload() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('CardCreator',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
            
        $setting_decksize = Setting::getByName('cards_decksize');
        
        if($setting_decksize instanceof Setting){
            
            $data['settings']['decksize'] = $setting_decksize->getValue();
            
        }else{
            
            $data['_error'][] = $setting_decksize;
            
        }
        
        $data['categories'] = Category::getALL();
        
        foreach($data['categories'] as $category){
            
            $cat_id = $category->getId();
            $data['subcategories'][$cat_id] = Subcategory::getByCategory($cat_id);
            
        }
        
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
            catch(Exception $e){
                die($e->getMessage());
            }
            
        }
        
        Layout::render('admin/deck/upload.php', $data);
        
    }
    
    /**
     * Admin Deck List
     */
    public function adminDeckList() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('CardCreator',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
            
        $data['carddecks'] = Carddeck::getAll();
        $data['badge_css']['new'] = 'badge-primary';
        $data['badge_css']['public'] = 'badge-secondary';
        
        Layout::render('admin/deck/list.php',$data);
            
    }
    
    /**
     * Deck edit
     */
    public function deckEdit() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('CardCreator',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
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
        
        Layout::render('admin/deck/edit.php',$data);
        
    }
    
}