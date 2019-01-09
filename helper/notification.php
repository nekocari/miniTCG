<?php
/**
 * helper to set up notifications 
 * for new trades or new messages 
 * 
 * @author Cari
 * 
 */

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
    
    /**
     * returns a counter of new trades + counter of new messages 
     * @return int
     */
    public function getAll(){
        return $this->getTradeCountNew() + $this->getMessageCountNew();
    }
    
    /**
     * returns number of new trades
     * @return int
     */
    public function getTradeCountNew(){
        if($this->user_id){
            return $trade_notifications = count(Trade::getRecievedByMemberId($this->user_id, 'new'));
        }else{
            return 0;
        }
    }
    
    /**
     * returns number of new messages
     * @return int
     */
    public function getMessageCountNew(){
        if($this->user_id){
            return $message_notifications = count(Message::getReceivedByMemberId($this->user_id, 'new'));
        }else{
            return 0;
        }
    }
    
}

?>