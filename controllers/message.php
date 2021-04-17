<?php
/*
 * Message Controller
 */

require_once 'models/message.php';
require_once 'helper/pagination.php';

class MessageController {
    
    /**
     * write new Message
     */
    public function write() {
        
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        $data['text'] = '';
        $data['recipient'] = 0;
        
        if(isset($_POST['action'])){
                        
            // check if post has got all relavant keys
            if(count(array_diff_key(['text'=>null,'recipient'=>null], $_POST)) == 0){
                $data['text'] = $_POST['text'];
                $data['recipient'] = $_POST['recipient'];
                try{
                    // add the new message to the database
                    $message = Message::add(Login::getUser()->getId(), $_POST['recipient'], $_POST['text']);
                    // check if message is a valid Message object
                    if($message instanceof Message){
                        // set success message text
                        $data['_success'][] = SystemMessages::getSystemMessageText('pm_send_success');
                    }else{
                        // log error
                        error_log('Message::add() returned wrong type:'.gettype($message).'\n', 3, ERROR_LOG);
                        // error message text
                        $data['_error'][] = SystemMessages::getSystemMessageText('unexpected_error');
                    }
                }
                catch(Exception $e){
                    // erro message text
                    $data['_error'][] = SystemMessages::getSystemMessageText('pm_send_failure')."<br>".$e->getMessage();
                }
            }else{
                // error message text
                $data['_error'][] = SystemMessages::getSystemMessageText('pm_missing_input');
            }
        }
        
        $data['recipients'] = Member::getAll(['name'=>'ASC']);
        
        Layout::render('message/new.php',$data);
    }
    
    /**
     * Messages sent
     */
    public function sent() {
        $data = $this->folder_actions('sent');
        Layout::render('message/sent.php',$data);        
    }
    
    /**
     * Messages received
     */
    public function received() {
        $data = $this->folder_actions('recieved');
        Layout::render('message/received.php',$data);
    }
    
    private function folder_actions($category='recieved',$pagination=true,$max_elements=5){
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();
        
        if(count($_POST)){
            $data = $this->processPostAction();
        }
        // current page for pagination
        $currPage = 1;
        if(isset($_GET['pg'])){
            $currPage = intval($_GET['pg']);
        }
        
        // determine user type 
        if(!in_array($category,['recieved','sent'])){
            $category = 'recieved';
        }
        if($category == 'recieved'){
            $user_type = 'recipient';
        }else{
            $user_type = 'sender';
        }
        // get array of messages or set up an error message in case of failure
        $msgs = Message::getByMemberId(Login::getUser()->getId(), $user_type);
        
        // set up the pagination and pass values on to view
        $pagination = new Pagination($msgs, $max_elements, $currPage, Routes::getUri('messages_'.$category));
        
        $data['msgs'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        return $data;
    }
    
    private function processPostAction(){
        
        $action = null;
        
        if(isset($_POST['read'])){
            $action = 'read';
            $post_id = $_POST['read'];
            $success_msg_key = 'pm_mark_read';
            $failure_msg_key = 'pm_mark_read_failure';
        }
        
        if(isset($_POST['delete'])){
            $action = 'delete';
            $post_id = $_POST['delete'];
            $success_msg_key = 'pm_deleted';
            $failure_msg_key = 'pm_delete_failure';
        }
        switch($action){
            case 'read':
            case 'delete':
                try{
                    $msg = Message::getByPk($post_id);
                    if($msg instanceof Message){
                        $result = $msg->{$action}();
                        //var_dump($result);
                        if($result){
                            $data['_success'][] = SystemMessages::getSystemMessageText($success_msg_key);
                        }else{
                            $data['_error'][] = SystemMessages::getSystemMessageText($failure_msg_key);
                        }
                    }else{
                        $data['_error'][] = SystemMessages::getSystemMessageText('pm_not_found');
                    }
                }
                catch(Exception $e){
                    $data['_error'][] = SystemMessages::getSystemMessageText('unexpected_error').'<br>'.$e->getMessage();
                }
                break;
        }
        return $data;
    }
    
}
?>