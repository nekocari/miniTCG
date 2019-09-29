<?php
/*
 * Message Controller
 */

require_once 'models/message.php';
require_once 'helper/pagination.php';

class MessageController {
    
    /**
     * Messages received
     */
    public function received() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        
        // mark messsage as read if formdata was sent
        if(isset($_POST['read'])){
            if(Message::read($_POST['read'])){
                $data['_success'][] = SystemMessages::getSystemMessageText('pm_mark_read');
            }
        }
        
        // delete message if formdata was sent
        if(isset($_POST['delete'])){
            if(Message::delete($_POST['delete'])){
                $data['_success'][] = SystemMessages::getSystemMessageText('pm_deleted');
            }
        }
        
        // current page for pagination 
        if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // get array of messages or set up an error message in case of failure
        $msgs = Message::getReceivedByMemberId($_SESSION['user']->id);
        if(!is_array($msgs)){ 
            $data['_error'][] = $msgs;
            $msgs = array(); 
        }
        
        // set up the pagination and pass values on to view
        $pagination = new Pagination($msgs, 20, $currPage, Routes::getUri('messages_received'));
        $data['msgs'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        Layout::render('message/received.php',$data);
    }
    
}
?>