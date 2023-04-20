<?php
/*
 * Trade Controller
 * 
 * @author NekoCari
 */

class TradeController extends AppController {
	
	public function __construct() {
		parent::__construct();
		$this->redirectNotLoggedIn();
	}
    
    /**
     * Trades recieved
     */
    public function recieved() {
        
        $data = array();
        
        // trade was declined
        if(isset($_POST['decline']) AND isset($_POST['id']) AND isset($_POST['text'])){
            
            $trade = Trade::getById($_POST['id']);
            
            // try db update an set messages in case of success or failure
            if($trade->decline($this->login(),$_POST['text'])){
            	$this->layout()->addSystemMessage('success','trade_decline_success');
            }else{
                $this->layout()->addSystemMessage('error','trade_decline_failed');
            }
        }
        
        // trade was accepted
        if(isset($_POST['accept']) AND isset($_POST['id']) AND isset($_POST['text'])){
            
            $trade = Trade::getById($_POST['id']);
            
            // try db update an set messages in case of success or failure
            if($trade->accept($this->login(),$_POST['text'])){
                $this->layout()->addSystemMessage('success','trade_accept_success');
            }else{
                $this->layout()->addSystemMessage('error','trade_accept_failed');
            }
        }
        
        // fetch current trade offers from database
        $data['trades'] = Trade::getRecievedByMemberId($this->login()->getUser()->getId());
        
        $this->layout()->render('trade/recieved.php',$data);
    }
    
    /**
     * Trades sent
     */
    public function sent() {
        
        
        // delete a trade offer
        if(isset($_POST['delete']) AND isset($_POST['id']) ){
            
            try{
                $trade = Trade::getById($_POST['id']);
                if($trade instanceof Trade){
                    if($trade->delete()){
                        $this->layout()->addSystemMessage('success','trade_delete_success');
                    }
                }else{
                	$this->layout()->addSystemMessage('error','invalid_data');
                }
            }
            catch (Exception $e){
                $this->layout()->addSystemMessage('error','trade_delete_failed');
                error_log($e->getMessage().PHP_EOL, 3, ERROR_LOG);
            }
        }
        
        // get all sent trades
        $trades= Trade::getSentByMemberId($this->login()->getUserId());
        
        $this->layout()->render('trade/sent.php',['trades'=>$trades]);
    }
    
    /**
     * make a new Trade offer
     */
    public function add() {
        
        // check if a card was selected
        if(!empty($_GET['card'])){
            
            $data = array();
            // get data of selected card
            $data['requested_card'] = Card::getById($_GET['card']);
            //var_dump($data);
            
            // form was submited
            if(isset($_POST['add_trade']) AND !empty($_POST['offered_card_id'])){
                
                // get data of offered card
                $data['offered_card'] = Card::getById($_POST['offered_card_id']);
                
                // try to add the new trade offer to the database and create messages for case of success and failure
                try{
                    Trade::add($_POST['recipient'],$this->login()->getUserId(),$_POST['requested_card_id'],$_POST['offered_card_id'],$_POST['text']);
                    $this->layout()->addSystemMessage('success','trade_new_success');
                }
                catch (ErrorException $e){
                	error_log($e->getMessage().PHP_EOL, 3, ERROR_LOG);
                }
                catch (Exception $e){
                	$this->layout()->addSystemMessage('error','trade_new_failed');
                }
                
                $this->layout()->render('trade/add_result.php',$data);
            
            // form was not subbmitted
            }else{
                
                // check if trade user is not logged in user
                if($data['requested_card']->getOwner()->getId() != $this->login()->getUser()->getId()){
                    $data['searchcardurl'] = Card::getSearchcardHtml(); // TODO: filler for trade page?
                    // get all tradeable cards from logged in user 
                    $data['cards'] = $this->login()->getUser()->getTradeableCards($data['requested_card']->getOwner()->getId());
                    //die(var_dump($data['cards']));
                    $this->layout()->render('trade/add.php',$data);
                    
                }else{
                    
                    // error message in case user is logged in user
                    $this->layout()->addSystemMessage('error','trade_with_self');
                    $this->layout()->render('templates/error.php', $data);
                    
                }
            }
        
        // no card was selected
        }else{
            
            // display an error message
            $this->layout()->addSystemMessage('error','2001');
            $this->layout()->render('templates/error.php', $data);
            
        }
    }
    
}
?>