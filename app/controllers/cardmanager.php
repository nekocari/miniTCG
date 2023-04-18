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
    	$cardmanager = new Cardmanager($this->login()->getUser(),'join'); // if join runs slow, try using exists
        
        // a card status was selected and is valid then set as status
        if(isset($_GET['status']) AND array_key_exists($_GET['status'], Card::getAcceptedStati())){
            $cardmanager->setStatus(CardStatus::getById($_GET['status']));            
        }elseif(!isset($_POST['status'])){
            // set status to new by default
        	$cardmanager->setStatus(CardStatus::getNew());
        }else{
        	// a status is given but invalid then set error message
        	$this->layout()->addSystemMessage('error', 'cardmanager_status_invalid', array(), '<i>'.$_GET['status'].'</i>');
        }
        
        if(isset($_POST['changeCardStatus'])){
        	$this->processActions('move_cards');
        }
        
                
        // card status
        if($cardmanager->getStatus() != CardStatus::getCollect()){
            
        	$data['cardmanager'] = $cardmanager;
            // paginate cards
            $pagination = new Pagination($cardmanager->getCards(), 35, Routes::getUri('member_cardmanager').'?status='.$cardmanager->getStatus()->getId());
            $pagination->checkForCurrPage();
            $data['cards'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml();
            
            $this->layout()->render('login/cardmanager.php',$data);
            
            
        }else{
            // TODO
            /*
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
            }*/
        	$data['cardmanager'] = $cardmanager;
            
            $this->layout()->render('login/collectmanager.php',$data);
        }
    }
    
    private function processActions($action){
    	
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
    		
    		// set messages for case of sucCess and for failure
    		if($updated === true){
    			$this->layout()->addSystemMessage('success', 'cardmanager_master_deck_success');
    		}else{
    			$this->layout()->addSystemMessage('error', 'cardmanager_master_deck_failed', array(), ' - database error: '.$updated);
    		}
    	}
    	switch($action){
    		case 'move_cards':
    			if(isset($_POST['newStatus']) AND is_array($_POST['newStatus'])){
    				// go throug each card, set the new status
    				foreach($_POST['newStatus'] as $card_id => $new_status){
    					
    					// new status is not empty and contains an accepted value
    					if($new_status != "" AND array_key_exists($new_status, Card::getAcceptedStati()) ){
    						$card = Card::getById($card_id);
    						$collect_status = CardStatus::getCollect();
    						if($new_status != $collect_status->getId() OR !Card::existsInStatus($this->login()->getUser(),$card,$collect_status)){
    							$card->setStatusId($new_status);
    							$card->update();
    						}else{
    							$this->layout()->addSystemMessage('error', 'cardmanager_move_card_failed', array(), '<i>'.$card->getName().'</i>');
    						}
    					}
    				}
    			}
    			break;
    		case 'master_collection':
    			if(isset($_POST['deck_id'])){
    				
    			}
    			break;
    		case 'dissolve_collection':
    			if(isset($_POST['deck_id'])){
    				
    			}
    			break;
    	}
    }
    
}
?>