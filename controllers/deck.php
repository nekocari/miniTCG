<?php
/*
 * Controller for deck related pages
 */
class DeckController {
    
    public function index() {
        require_once 'models/category.php';
        require_once 'models/subcategory.php';
        require_once 'models/carddeck.php';
        require_once 'helper/pagination.php';
        
        if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        $decks = Carddeck::getAllByStatus('public');
        $pagination = new Pagination($decks, 10, $currPage, 'decks.php');
        
        $data = array();
        $data['decks'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        if(count($data['decks']) == 0){
            $data['_error'][] = 'Ungültige Seite: Keine Elemente zum Anzeigen.';
        }
        
        Layout::render('deck/list.php', $data);
    }
    
    public function category() {
        if(isset($_GET['id'])){
            try {
                require_once 'models/category.php';
                require_once 'models/subcategory.php';
                require_once 'models/carddeck.php';
                
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
            
        }
        
    }
    
    public function deckpage() {
        if(isset($_SESSION['user']) AND isset($_GET['id'])){
            require_once 'models/carddeck.php';
            require_once 'models/setting.php';
            
            $data = array();
            $data['deck'] = Carddeck::getById($_GET['id']);
            
            if($data['deck'] instanceof Carddeck){
                
                Layout::render('deck/deckpage.php',$data);
                
            }else{
                header('Location: '.BASE_URI.'error.php');
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
            header("Location: ".BASE_URI."signin.php");
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            
            require_once 'models/setting.php';
            $setting_decksize = Setting::getByName('cards_decksize');
            if($setting_decksize instanceof Setting){
                $data['settings']['decksize'] = $setting_decksize->getValue();
            }else{
                $data['_error'][] = $setting_decksize;
            }
            
            require_once PATH.'models/subcategory.php';
            $data['categories'] = Category::getALL();
            foreach($data['categories'] as $category){
                $cat_id = $category->getId();
                $data['subcategories'][$cat_id] = Subcategory::getByCategory($cat_id);
            }
            
            if(isset($_POST['upload']) AND isset($_POST['name'],$_POST['deckname'],$_POST['subcategory']) AND isset($_FILES)){
                require_once 'helper/cardupload.php';
                $upload = new CardUpload($_POST['name'], $_POST['deckname'], $_FILES, $_SESSION['user']->id, $_POST['subcategory']);
                if(($upload_status = $upload->store()) === true){
                    $data['_success'][] = 'Karten wurde erfolgreich hochgeladen.';
                }else{
                    $data['_error'][] = 'Upload nicht abgeschlossen: '.$upload_status;
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
            header("Location: ".BASE_URI."signin.php");
        }
        require_once 'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            require_once 'models/carddeck.php';
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
            header("Location: ".BASE_URI."signin.php");
        }
        require_once 'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            require_once 'models/carddeck.php';
            require_once 'models/setting.php';
            require_once 'models/member.php';
            require_once 'models/subcategory.php';
            
            if(isset($_POST['updateDeckdata'],$_POST['id'], $_POST['name'], $_POST['creator'])){
                $update = Carddeck::update($_POST['id'], $_POST['name'], $_POST['creator'], $_POST['subcategory']);
                if($update === true){
                    $data['_success'][] = 'Daten wurden gespeichert';
                }else{
                    $date['_error'][] = $update;
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