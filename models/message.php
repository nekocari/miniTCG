<?php
/**
 * 
 * Represents a Message
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/member.php';
require_once PATH.'helper/Parsedown.php';

class Message extends DbRecordModel {
    
    protected $id, $date, $sender_id, $recipient_id, $text, $status, $deleted;
    
    private $sender_obj, $recipient_obj;
    
    protected static
        $db_table = 'messages',
        $db_pk = 'id',
        $db_fields = array('id','date','sender_id','recipient_id','text','status','deleted'),
        $sql_order_by_allowed_values = array('id','date','status','sender','recipient');
        
        private static $accepted_status_values = array('new','all');
        private static $accepted_deleted_values = array('sender','recipient');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * get message objects for member - msg status optional
     * @param int $id member id
     * @param string $status new|all
     * @return Message[]
     */
    public static function getReceivedByMemberId($id, $status = 'all') {
        return self::getByMemberId($id, 'recipient', $status);
    }
    
    /**
     * get message objects sent by member - status optional
     * @param int $id member id
     * @param string $status new|all
     * @return Message[]
     */
    public static function getSentByMemberId($id, $status = 'all') {
       return self::getByMemberId($id, 'sender', $status);
    }
    
    /**
     * get message objects by member id
     * @param int $id
     * @param string $type
     * @param string $tatus
     * @return Message[]
     */
    public static function getByMemberId($id, $type, $status='all'){
        if(!in_array($type,['sender','recipient'])){
            $type = 'recipient';
        }
        if(!in_array($status, self::$accepted_status_values)){
            $status = self::$accepted_status_values[0];
        }
        
        $msgs = array();
        
        $where = $type.'_id = '.intval($id).' AND (deleted != \''.$type.'\' OR deleted IS NULL) ';
        
        if($status == 'new'){
            $where.= ' AND status = \'new\'';
        }
        
        return parent::getWhere($where,['date'=>'DESC']);
    }
    
    /**
     * adds/sends a new message 
     * @param int $sender
     * @param int $recipient
     * @param string $text
     * @return Message
     */
    public static function add($sender, $recipient, $text){
        // remove HTML
        $text = strip_tags($text);
        
        $message = new Message();
        $message->setPropValues(['sender_id'=>$sender, 'recipient_id'=>$recipient, 'text'=>$text]);
        $message_id = $message->create();
        $message->setPropValues(['id'=>$message_id]);
        
        return $message;
    }
    
    /**
     * change msg status to read and updates database entry;
     * @return boolean
     */
    public function read(){
        $this->status = 'read';
        return $this->update();
    }
    
    /**
     * returns if true if message is unread
     * @return boolean
     */
    public function isNew() {
        $is_new = false;
        if($this->status == 'new'){
            $is_new = true;
        }
        return $is_new;
    }
    
    /**
     * sets a delete flag or deletes if msg was already flagged by the other party
     * @see DbRecordModel::delete()
     * @return boolean
     */
    public function delete(){
        
        if(Login::loggedIn()){
            $user_id = Login::getUser()->getId();
            $user_type = NULL;
            if($this->recipient_id == $user_id){
                $user_type = 'recipient';
            }else{
                $user_type = 'sender';
            }
            
            // user is recipient and sender is null -> system message
            if($user_type == 'recipient' AND $this->sender_id == NULL){
                parent::delete();
                return true;
            }
            
            // message has not been deleted by anyone -> NULL
            if($this->deleted == NULL){
                // set deleted to current user type and update
                $this->deleted = $user_type;
                $this->update();
                return true;
            }else{
                // if deleted is other than current user type
                if($this->deleted != $user_type){
                    // delete db entry 
                    parent::delete();
                    return true;
                }else{
                    // do nothing
                }
            }
        }
        return false;
    }
    
    // GETTER
    
    public function getId() {
        return $this->id;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getSender() {
        if(!$this->sender_obj instanceof Member){
            $this->sender_obj = Member::getById($this->sender_id);
            if(is_null($this->sender_obj)){
                $this->sender_obj = new Member();
                $this->sender_obj->setName('System');
            }
        }
        return $this->sender_obj;
    }
    
    public function getRecipient() {
        if(!$this->recipient_obj instanceof Member){
            $this->recipient_obj = Member::getById($this->recipient_id);
        }
        return $this->recipient_obj;
    }
    
    public function getText() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->text);
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    
}