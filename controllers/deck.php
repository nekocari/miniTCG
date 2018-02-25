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
    
}