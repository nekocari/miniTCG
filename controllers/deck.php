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
        
        $decks = Carddeck::getAllByStatus('public');
        $pagination = new Pagination($decks, 10, $currPage, Routes::getUri('deck_index'));
        
        $data = array();
        $data['decks'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        if(count($data['decks']) == 0){
            
            $data['_error'][] = SystemMessages::getSystemMessageText('deck_no_elements');
            
        }
        
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
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            
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
            
            if(isset($_POST['upload']) AND isset($_POST['name'],$_POST['deckname'],$_POST['subcategory']) AND isset($_FILES)){
                
                $upload = new CardUpload($_POST['name'], $_POST['deckname'], $_FILES, $_SESSION['user']->id, $_POST['subcategory']);
                
                if(($upload_status = $upload->store()) === true){
                    
                    $data['_success'][] = SystemMessages::getSystemMessageText('deck_upload_success');
                    
                }else{
                
                    $data['_error'][] = SystemMessages::getSystemMessageText('deck_upload_failed').' - '.$upload_status;
                    
                }
                
            }
            
            Layout::render('admin/deck/upload.php', $data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * Admin Deck List
     */
    public function adminDeckList() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            
            $data['carddecks'] = Carddeck::getAll();
            $data['badge_css']['new'] = 'badge-primary';
            $data['badge_css']['public'] = 'badge-secondary';
            
            Layout::render('admin/deck/list.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * Deck edit
     */
    public function deckEdit() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            
            if(isset($_POST['updateDeckdata'],$_POST['id'], $_POST['name'], $_POST['creator'])){
                
                $update = Carddeck::update($_POST['id'], $_POST['name'], $_POST['creator'], $_POST['subcategory']);
                
                if($update === true){
                    
                    $data['_success'][] = SystemMessages::getSystemMessageText('deck_edit_success');
                    
                }else{
                    
                    $data['_error'][] = SystemMessages::getSystemMessageText('deck_edit_failed').' - '.SystemMessages::getSystemMessageText($update);
                    
                }
                
            }
            
            $data['categories'] = Category::getALL();
            
            foreach($data['categories'] as $category){
                
                $cat_id = $category->getId();
                $data['subcategories'][$cat_id] = Subcategory::getByCategory($cat_id);
                
            }
            
            $data['deckdata'] = Carddeck::getById($_GET['id']);
            $data['card_images'] = $data['deckdata']->getImages();
            $data['memberlist'] = Member::getAll('name', 'ASC');
            
            Layout::render('admin/deck/edit.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
}