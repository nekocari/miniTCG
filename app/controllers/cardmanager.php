<?php
/*
 * Cardmanager
 * 
 * @author NekoCari
 */


class CardmanagerController extends AppController {
    
    /**
     * card manager
     */
    public function cardmanager() {
        
    	$this->redirectNotLoggedIn();
        
        // a card status was selected and is valid then store it in $status
        if(isset($_GET['status']) AND in_array($_GET['status'], Card::getAcceptedStati())){
            $status = $_GET['status'];
            
        }else{
            // set $status to new by default
            $status = 'new';
            
            // a status is given but invalid then set error message
            if(isset($_GET['status'])){
            	$this->layout()->addSystemMessage('error', 'cardmanager_status_invalid', array(), '<i>'.$_GET['status'].'</i>');
            }
        }
        
        // if form to change card status was submited
        if(isset($_POST['changeCardStatus'])){
            
            // go throug each card, set the new status
            foreach($_POST['newStatus'] as $card_id => $new_status){
                
                // new status is not empty and contains an accepted value
                if($new_status != "" AND in_array($new_status, Card::getAcceptedStati()) ){
                    
                    // do the database update
                    $updated = Card::changeStatusById($card_id,$new_status, $this->login()->getUser()->getId());
                    
                    // if it was not successfull set an error message
                    if($updated == false){
                        
                    	$card_name = Card::getById($card_id)->getName();
                    	$this->layout()->addSystemMessage('error', 'cardmanager_move_card_failed', array(), '<i>'.$card_name.'</i>');
                        
                    }
                }
            }
        }
        
        // form to dissolve a collection was send
        if(isset($_POST['dissolve'])){
            
            // do the database update
            $updated = Card::dissolveCollection($_POST['dissolve'], $this->login()->getUser()->getId());
            
            // set messages for case of sucess and for failure
            if($updated === true){
            	$this->layout()->addSystemMessage('success', 'cardmanager_dissolve_collection_success');
            }else{
            	$this->layout()->addSystemMessage('error', 'cardmanager_dissolve_collection_failed');
            }
            
        }
        
        // form to master a deck was send
        if(isset($_POST['master'])){
            
            // do the database update
            $updated = Carddeck::master($_POST['master'], $this->login()->getUser()->getId());
            
            // set messages for case of sucess and for failure
            if($updated === true){
            	$this->layout()->addSystemMessage('success', 'cardmanager_master_deck_success');
            }else{
            	$this->layout()->addSystemMessage('error', 'cardmanager_master_deck_failed', array(), ' - database error: '.$updated);
            }
        }
        
        // current page for pagination
        if(isset($_GET['pg']) AND intval($_GET['pg'])>0 ){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // store vars to be usable in view
        $cardmanager = new Cardmanager($this->login()->getUser()->getId(),'join'); // if join runs slow, try using exists
        $data['curr_status'] = $status;
        $data['accepted_status'] = Card::getAcceptedStati();
        $data['cards'] = $cardmanager->getCardsByStatus($status);
        //var_dump($data['cards']);
        // card status
        if($status != 'collect'){
            
            
            // paginate cards
            $pagination = new Pagination($data['cards'], 35, $currPage, RoutesDb::getUri('member_cardmanager').'?status='.$status);
            $data['cards'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml();
            
            $this->layout()->render('login/cardmanager.php',$data);
            
            
        }else{
            
            // store some more vars for the view
            $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
            $data['cards_per_row'] = Setting::getByName('deckpage_cards_per_row')->getValue();
            $data['collections'] = array();
            $data['searchcard_html'] = Card::getSearchcardHtml();
            $data['use_special_puzzle_filler'] = $use_special_puzzle_filler = Setting::getByName('card_filler_use_puzzle')->getValue();
            for($i = 1; $i <= Setting::getByName('cards_decksize')->getValue(); $i++){
                if($use_special_puzzle_filler == 0){
                    $data['searchcard_html_'.$i] = $data['searchcard_html'];
                }else{
                    $data['searchcard_html_'.$i] = Card::getSearchcardHtml('puzzle',$i);
                }
            }
            
            // go throug the cards an build collection array for each deck
            foreach($data['cards'] as $card){
                
                $data['collections'][$card->getDeckId()][$card->getNumber()] = $card;
                
                if(!isset($data['deckdata'][$card->getDeckId()])){
                    
                    $data['deckdata'][$card->getDeckId()] = Carddeck::getById($card->getDeckId());
                    
                }
            }
            
            $this->layout()->render('login/collectmanager.php',$data);
        }
    }
    
}
?>