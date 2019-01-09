<?php
require_once PATH.'models/login.php';
require_once PATH.'models/trade.php';
require_once PATH.'models/message.php';


class Notification {
    
    private $db, $user_id=false, $trade_notifikations = false, $message_notifications = false;
    
    public function __construct() {
        if(Login::loggedIn()){
            $this->db = Db::getInstance();
            $this->user_id = $_SESSION['user']->id;
        }
    }
    
    public function getAll(){
        return $this->getTradeCountNew() + $this->getMessageCountNew();
    }
    
    public function getTradeCountNew(){
        if($this->user_id){
            return $trade_notifications = count(Trade::getRecievedByMemberId($this->user_id, 'new'));
        }else{
            return 0;
        }
    }
    
    public function getMessageCountNew(){
        if($this->user_id){
            return $message_notifications = count(Message::getReceivedByMemberId($this->user_id, 'new'));
        }else{
            return 0;
        }
    }
    
}

?>