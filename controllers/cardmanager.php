<?php
/*
 * Cardmanager
 */
require_once PATH.'models/login.php';
require_once PATH.'models/card.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'helper/pagination.php';
require_once PATH.'models/master.php';
require_once PATH.'models/member.php';

class CardmanagerController {
    
    /**
     * card manager
     */
    public function cardmanager() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // a card status was selected and is valid then store it in $status
        if(isset($_GET['status']) AND in_array($_GET['status'], Card::getAcceptedStati())){
            $status = $_GET['status'];
            
            // set $status to new by default
        }else{
            $status = 'new';
            
            // a status is given but invalid then set error message
            if(isset($_GET['status'])){
                $data['_error'][] = SystemMessages::getSystemMessage('cardmanager_status_invalid')." <i>".$_GET['status']."</i>";
            }
        }
        
        // if form to change card status was submited
        if(isset($_POST['changeCardStatus'])){
            
            // go throug each card, set the new status
            foreach($_POST['newStatus'] as $card_id => $new_status){
                
                // new status is not empty and contains an accepted value
                if($new_status != "" AND in_array($new_status, Card::getAcceptedStati()) ){
                    
                    // do the database update
                    $updated = Card::changeStatusById($card_id,$new_status, Login::getUser()->getId());
                    
                    // if it was not successfull set an error message
                    if($updated == false){
                        
                        $card_name = Card::getById($card_id)->getName();
                        $data['_error'][] = SystemMessages::getSystemMessageText('cardmanager_move_card_failed').' <i>'.$card_name.'</i>';
                        
                    }
                }
            }
        }
        
        // form to dissolve a collection was send
        if(isset($_POST['dissolve'])){
            
            // do the database update
            $updated = Card::dissolveCollection($_POST['dissolve'], Login::getUser()->getId());
            
            // set messages for case of sucess and for failure
            if($updated === true){
                $data['_success'][] = SystemMessages::getSystemMessageText('cardmanager_dissolve_collection_success');
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('cardmanager_dissolve_collection_failed');;
            }
            
        }
        
        // form to master a deck was send
        if(isset($_POST['master'])){
            
            // do the database update
            $updated = Carddeck::master($_POST['master'], Login::getUser()->getId());
            
            // set messages for case of sucess and for failure
            if($updated === true){
                $data['_success'][] = SystemMessages::getSystemMessageText('cardmanager_master_deck_success');
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('cardmanager_master_deck_failed').' - database error: '.$updated;
            }
        }
        
        // current page for pagination
        if(isset($_GET['pg']) AND intval($_GET['pg'])>0 ){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // store vars to be usable in view
        $data['curr_status'] = $status;
        $data['accepted_status'] = Card::getAcceptedStati();
        $data['cards'] = Card::getMemberCardsByStatus(Login::getUser()->getId(),$status);
        
        // card status
        if($status != 'collect'){
            
            
            // paginate cards
            $pagination = new Pagination($data['cards'], 35, $currPage, Routes::getUri('member_cardmanager').'?status='.$status);
            $data['cards'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml();
            
            Layout::render('login/cardmanager.php',$data);
            
            
        }else{
            
            // store some more vars for the view
            $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
            $data['cards_per_row'] = Setting::getByName('deckpage_cards_per_row')->getValue();
            $data['searchcard_html'] = Card::getSerachcardHtml();
            $data['collections'] = array();
            
            // go throug the cards an build collection array for each deck
            foreach($data['cards'] as $card){
                
                $data['collections'][$card->getDeckId()][$card->getNumber()] = $card;
                
                if(!isset($data['deckdata'][$card->getDeckId()])){
                    
                    $data['deckdata'][$card->getDeckId()] = Carddeck::getById($card->getDeckId());
                    
                }
            }
            
            Layout::render('login/collectmanager.php',$data);
        }
    }
    
}
?>