<?php
/*
 * Message Controller
 */

class MessageController {
    
    /**
     * Messages received
     */
    public function received() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once 'models/message.php';
        require_once 'helper/pagination.php';
        
        $data = array();
        
        if(isset($_POST['read'])){
            if(Message::read($_POST['read'])){
                $data['_success'][] = 'Nachricht als gelesen markiert.';
            }
        }
        
        if(isset($_POST['delete'])){
            if(Message::delete($_POST['delete'])){
                $data['_success'][] = 'Nachricht wurde gelöscht.';
            }
        }
        
        if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        $msgs = Message::getReceivedByMemberId($_SESSION['user']->id);
        if(!is_array($msgs)){ 
            $data['_error'][] = $msgs;
            $msgs = array(); 
        }
        
        $pagination = new Pagination($msgs, 20, $currPage, Routes::getUri('messages_received'));
        
        $data['msgs'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        Layout::render('message/received.php',$data);
    }
    
}
?>