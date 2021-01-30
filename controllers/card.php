<?php
/*
 * Controller for card related pages
 */
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
    
}