<?php
/*
 * Controller for deck related pages
 */
class DeckController {
    
    public function index() {
        require_once 'models/category.php';
        require_once 'models/subcategory.php';
        require_once 'models/carddeck.php';
        
        $date = array();
        $data['decks'] = Carddeck::getAllByStatus('public');
        
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
        require_once 'models/carddeck.php';
    }
    
}