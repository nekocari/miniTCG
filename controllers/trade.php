<?php
/*
 * Trade Controller
 */

class TradeController {
    
    /**
     * Trades recieved
     */
    public function recieved() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once 'models/trade.php';
        
        $data = array();
        $data['trades'] = Trade::getRecievedByMemberId($_SESSION['user']->id);
        
        Layout::render('trade/recieved.php',$data);
    }
    
    /**
     * Trades sent
     */
    public function sent() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once 'models/trade.php';
        
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
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        if(!empty($_GET['card'])){
            require_once 'models/card.php';
            
            $data = array();
            
            $data['cards'] = Card::getMemberCardsByStatus($_SESSION['user']->id, 'trade');
            $data['requested_card'] = Card::getById($_GET['card']); 
            
            Layout::render('trade/add.php',$data);
        }else{
            $data['_error'][] = 'Keine gültige Auswahl getroffen!';
            Layout::render('templates/error.php', $data);
        }
    }
    
}
?>