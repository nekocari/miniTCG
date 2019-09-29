<?php
/*
 * Trade Controller
 */

require_once PATH.'models/login.php';
require_once PATH.'models/trade.php';
require_once 'models/card.php';

class TradeController {
    
    /**
     * Trades recieved
     */
    public function recieved() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        
        // trade was declined
        if(isset($_POST['decline']) AND isset($_POST['id']) AND isset($_POST['text'])){
            
            $trade = Trade::getById($_POST['id']);
            
            // try db update an set messages in case of success or failure
            if(($return = $trade->decline($_POST['text'])) === true){
                $data['_success'][] = SystemMessages::getSystemMessageText('trade_decline_success');
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('trade_decline_failed').' - '.SystemMessages::getSystemMessageText($return);
            }
        }
        
        // trade was accepted
        if(isset($_POST['accept']) AND isset($_POST['id']) AND isset($_POST['text'])){
            
            $trade = Trade::getById($_POST['id']);
            
            // try db update an set messages in case of success or failure
            if(($return = $trade->accept($_POST['text'])) === true){
                $data['_success'][] = SystemMessages::getSystemMessageText('trade_accept_success').' - '.SystemMessages::getSystemMessageText('trade_card_new_info').
                                      strtoupper($trade->getOfferedCard()->getName());
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('trade_accept_failed').' - '.SystemMessages::getSystemMessageText($return);
            }
        }
        
        // fetch current trade offers from database
        $data['trades'] = Trade::getRecievedByMemberId($_SESSION['user']->id);
        
        Layout::render('trade/recieved.php',$data);
    }
    
    /**
     * Trades sent
     */
    public function sent() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        
        // delete a trade offer
        if(isset($_POST['delete']) AND isset($_POST['id']) ){
            
            // try db update an set messages in case of success or failure
            if(($return = Trade::delete($_POST['id'])) === true){
                $data['_success'][] = SystemMessages::getSystemMessageText('trade_delete_success');
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('trade_delete_failed').' - '.SystemMessages::getSystemMessageText($return);
            }
        }
        
        // get all sent trades
        $data['trades'] = Trade::getSentByMemberId($_SESSION['user']->id);
        
        Layout::render('trade/sent.php',$data);
    }
    
    /**
     * make a new Trade offer
     */
    public function add() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if a card was selected
        if(!empty($_GET['card'])){
            
            $data = array();
            // get data of selected card
            $data['requested_card'] = Card::getById($_GET['card']);
            
            // form was submited
            if(isset($_POST['add_trade'])){
                
                // get data of offered card
                $data['offered_card'] = Card::getById($_POST['offered_card_id']);
                
                // try to add the new trade offer to the database and create messages for case of success and failure
                if(TRADE::add($_POST['recipient'],$_POST['requested_card_id'],$_POST['offered_card_id'],$_POST['text'])){
                    $data['_success'][] = SystemMessages::getSystemMessageText('trade_new_success');
                }else{
                    $data['_error'][] = SystemMessages::getSystemMessageText('trade_new_failed');
                }
                
                Layout::render('trade/add_result.php',$data);
            
            // form was not subbmitted
            }else{
                
                // check if trade user is not logged in user
                if($data['requested_card']->getOwner()->getId() != $_SESSION['user']->id){
                    
                    // get all tradeable cards from logged in user 
                    $data['cards'] = Card::getMemberCardsByStatus($_SESSION['user']->id, 'trade', true);
                    Layout::render('trade/add.php',$data);
                    
                }else{
                    
                    // set up error message in case user is logged in user
                    $data['_error'][] = SystemMessages::getSystemMessageText('trade_with_self');
                    Layout::render('templates/error.php', $data);
                    
                }
            }
        
        // no card was selected
        }else{
            
            // display an error message
            $data['_error'][] = SystemMessages::getSystemMessageText('2001');
            Layout::render('templates/error.php', $data);
            
        }
    }
    
}
?>