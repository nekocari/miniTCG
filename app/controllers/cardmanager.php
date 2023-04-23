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
        
        if(isset($_POST['action'])){
        	$this->processActions($_POST['action']);
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
        	$data['cardmanager'] = $cardmanager;
        	// paginate Collections
        	$pagination = new Pagination($cardmanager->getCollectionDecks(), 2, Routes::getUri('member_cardmanager').'?status='.$cardmanager->getStatus()->getId());
        	$pagination->checkForCurrPage();
        	$data['collections'] = $pagination->getElements();
        	$data['pagination'] = $pagination->getPaginationHtml();
            
            $this->layout()->render('login/collectmanager.php',$data);
        }
    }
    
    private function processActions($action){
    	    	
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
    		case 'master':
    			if(isset($_POST['deck_id'])){
    				try{
	    				if(($deck = Carddeck::getById($_POST['deck_id'])) instanceof Carddeck AND $deck->master($this->login())){
	    					$this->layout()->addSystemMessage('success', 'cardmanager_master_deck_success', array(), '<i>'.$deck->getDeckname().'</i>');
	    				}else{
	    					$this->layout()->addSystemMessage('error', 'cardmanager_master_deck_failed');
	    				}
    				}
    				catch(Exception $e){
    					if($e->getCode() == 6003){
    						$this->layout()->addSystemMessage('error', 6003);
    					}
    				}
    			}
    			break;
    		case 'dissolve':
    			if(isset($_POST['deck_id'])){
    				if(($deck = Carddeck::getById($_POST['deck_id'])) instanceof Carddeck AND $deck->dissolveCollection($this->login())){
    					$this->layout()->addSystemMessage('success', 'cardmanager_dissolve_collection_success', array(), '<i>'.$deck->getDeckname().'</i>');
    				}else{
    					$this->layout()->addSystemMessage('error', 'cardmanager_dissolve_collection_failed');
    				}
    			}
    			break;
    	}
    }
    
}
?>