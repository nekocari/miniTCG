<?php
/*
 * Message Controller
 * 
 * @author NekoCari
 */

class MessageController extends AppController {
	
	public function __construct() {
		parent::__construct();
		$this->redirectNotLoggedIn();
	}
    
    /**
     * write new Message
     */
    public function write() {
        
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
                    $message = Message::add($this->login()->getUser()->getId(), $_POST['recipient'], $_POST['text']);
                    // check if message is a valid Message object
                    if($message instanceof Message){
                        // set success message text
                        $this->layout()->addSystemMessage('success','pm_send_success');
                    }else{
                        // log error
                        error_log('Message::add() returned wrong type:'.gettype($message).PHP_EOL, 3, ERROR_LOG);
                        // error message text
                        $this->layout()->addSystemMessage('error','unexpected_error');
                    }
                }
                catch(Exception $e){
                    // erro message text
                    $this->layout()->addSystemMessage('error','pm_send_failure')."<br>".$e->getMessage();
                }
            }else{
                // error message text
                $this->layout()->addSystemMessage('error','pm_missing_input');
            }
        }
        
        if(isset($_GET['id']) AND ($message = Message::getByPk($_GET['id'])) instanceof Message){
        	if($message->getRecipient()->getId() == $this->login()->getUserId()){
        		$data['message'] = $message;
        		$data['recipients'] = array(Member::getById($message->getSender()->getId()));
        	}
        }else{
        	$data['recipients'] = Member::getAll(['name'=>'ASC']);
        }
        
        $this->layout()->render('message/new.php',$data);
    }
    
    /**
     * Messages sent
     */
    public function sent() {
        $data = $this->folder_actions('sent');
        $this->layout()->render('message/sent.php',$data);        
    }
    
    /**
     * Messages received
     */
    public function received() {
        $data = $this->folder_actions('recieved');
        $this->layout()->render('message/received.php',$data);
    }
    
    private function folder_actions($category='recieved',$pagination=true,$max_elements=5){
                
        $data = array();
        
        if(count($_POST)){
            $data = $this->processPostAction();
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
        $msgs = Message::getByMemberId($this->login()->getUser()->getId(), $user_type);
        
        // set up the pagination and pass values on to view
        $pagination = new Pagination($msgs, $max_elements, Routes::getUri('messages_'.$category));    
        
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
            $action = 'deleteForUser';
            $post_id = $_POST['delete'];
            $success_msg_key = 'pm_deleted';
            $failure_msg_key = 'pm_delete_failure';
        }
        switch($action){
            case 'read':
            case 'deleteForUser':
                try{
                    $msg = Message::getByPk($post_id);
                    if($msg instanceof Message){
                        $result = $msg->{$action}($this->login());
                        if($result){
                            $this->layout()->addSystemMessage('success',$success_msg_key);
                        }else{
                            $this->layout()->addSystemMessage('error',$failure_msg_key);
                        }
                    }else{
                        $this->layout()->addSystemMessage('error','pm_not_found');
                    }
                }
                catch(Exception $e){
                    $this->layout()->addSystemMessage('error','unexpected_error').'<br>'.$e->getMessage();
                }
                break;
        }
    }
    
}
?>