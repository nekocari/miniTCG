<?php
/*
 * Trade Controller
 */

require_once PATH.'models/login.php';
require_once PATH.'models/trade.php';

class TradeController {
    
    /**
     * Trades recieved
     */
    public function recieved() {
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        
        if(isset($_POST['decline']) AND isset($_POST['id']) AND isset($_POST['text'])){
            $trade = Trade::getById($_POST['id']);
            if(($return = $trade->decline($_POST['text'])) === true){
                $data['_success'][] = 'Tauschanfrage wurde abgelehnt.';
            }else{
                $data['_error'][] = 'Tauschanfrage konnte nicht abgeleht werden. Fehlercode: '.$return;
            }
        }
        
        if(isset($_POST['accept']) AND isset($_POST['id']) AND isset($_POST['text'])){
            $trade = Trade::getById($_POST['id']);
            if(($return = $trade->accept($_POST['text'])) === true){
                $data['_success'][] = 'Tauschanfrage wurde angenommen. '.strtoupper($trade->getOfferedCard()->getName()).' findest du bei deinen Karten unter NEW';
            }else{
                $data['_error'][] = 'Tauschanfrage konnte nicht abgeleht werden. Fehlercode: '.$return;
            }
        }
        
        $data['trades'] = Trade::getRecievedByMemberId($_SESSION['user']->id);
        
        Layout::render('trade/recieved.php',$data);
    }
    
    /**
     * Trades sent
     */
    public function sent() {
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        
        if(isset($_POST['delete']) AND isset($_POST['id']) ){
            if(($return = Trade::delete($_POST['id'])) === true){
                $data['_success'][] = "Tauschangebot wurde gelöscht.";
            }else{
                $data['_error'][] = "Tauschangebot konnte nicht zurückgezogen werden: ".$return;
            }
        }
        
        $data['trades'] = Trade::getSentByMemberId($_SESSION['user']->id);
        
        Layout::render('trade/sent.php',$data);
    }
    
    /**
     * new Trade
     */
    public function add() {
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        require_once 'models/card.php';
        if(!empty($_GET['card'])){
            
            $data = array();
            $data['requested_card'] = Card::getById($_GET['card']);
            
            if(isset($_POST['add_trade'])){
                
                $data['offered_card'] = Card::getById($_POST['offered_card_id']); 
                if(TRADE::add($_POST['recipient'],$_POST['requested_card_id'],$_POST['offered_card_id'],$_POST['text'])){
                    $data['_success'][] = 'Taschanfrage wurde gesendet!';
                }else{
                    $data['_error'][] = 'Anfrage konnte nicht gestellt werden. Mindestens eine der Karten ist nicht mehr verfügbar.';
                }
                
                Layout::render('trade/add_result.php',$data);
                
            }else{
                if($data['requested_card']->getOwner()->getId() != $_SESSION['user']->id){
                    $data['cards'] = Card::getMemberCardsByStatus($_SESSION['user']->id, 'trade', true);
                    Layout::render('trade/add.php',$data);
                }else{
                    $data['_error'][] = 'Du kannst nicht mit dir selbst tauschen.';
                    Layout::render('templates/error.php', $data);
                }
            }
            
        }else{
            $data['_error'][] = 'Keine gültige Auswahl getroffen!';
            Layout::render('templates/error.php', $data);
        }
    }
    
}
?>