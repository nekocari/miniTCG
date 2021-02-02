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
            try{
                $msg = Message::getByPk($_POST['read']);
                if(!is_null($msg)){
                    $msg->read();
                    $data['_success'][] = SystemMessages::getSystemMessageText('pm_mark_read');
                }
            }
            catch(Exception $e){
                $data['_error'][] = $e->getMessage();
            }
        }
        
        // delete message if formdata was sent
        if(isset($_POST['delete'])){
            try{
                $msg = Message::getByPk($_POST['delete']);
                if(!is_null($msg)){
                    $msg->delete();
                    $data['_success'][] = SystemMessages::getSystemMessageText('pm_deleted');
                }
            }
            catch(Exception $e){
                $data['_error'][] = $e->getMessage();
            }
        }
        
        // current page for pagination 
        $currPage = 1;
        if(isset($_GET['pg'])){
            $currPage = intval($_GET['pg']);
        }
        
        // get array of messages or set up an error message in case of failure
        $msgs = Message::getReceivedByMemberId($_SESSION['user']->id);
                
        // set up the pagination and pass values on to view
        $pagination = new Pagination($msgs, 20, $currPage, Routes::getUri('messages_received'));
        
        $data['msgs'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        Layout::render('message/received.php',$data);
    }
    
}
?>