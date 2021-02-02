<?php
/*
 * Controller for card related pages
 */

require_once PATH.'models/carddeck.php';
require_once PATH.'models/card.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/admin.php';
require_once PATH.'helper/cardupload.php';

class CardController {
    
    function search() {
        
        if(!isset($_POST['deck_id']) OR !isset($_POST['number'])){
            
            $data['decks'] = Carddeck::getAllByStatus('public');
            $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
            
            Layout::render('card/search.php', $data);
            
        }else{
            
            $data['trader'] = Card::findTrader($_POST['deck_id'],$_POST['number']);
                        
            Layout::render('card/search_result.php',$data);
        }
        
    }
    
    
    function replaceImage(){
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){

            if(!empty($_GET['deck_id'])){
                
                $deck = Carddeck::getById($_GET['deck_id']);
                
                if($deck instanceof Carddeck){
                    
                    $data['deck'] = $deck;
                    $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
                    $data['card_keys'] = range(1,$data['decksize']);
                    array_push($data['card_keys'], '_master');
                    
                    if(isset($_POST) AND isset($_POST['number']) AND isset($_FILES['file']) ){
                        if(in_array($_POST['number'], $data['card_keys'])){
                            try{
                                $upload = CardUpload::replaceCardImage($deck->getId(), $_POST['number'], $_FILES['file']);
                            
                                if($upload){
                                    $data['_success'][] = 'Upload erfolgreich';
                                }else{
                                    $data['_error'][] = 'Upload fehlgeschlagen';
                                }
                            }
                            catch(Exception $e){
                                die($e->getMessage());
                            }
                        }
                    }
                    
                    
                    Layout::render('admin/deck/replace_card_image.php',$data);
                    
                    
                }else{
                    Layout::render('templates/error.php');
                }
            }
            
            
        }
        
    }
    
    
}