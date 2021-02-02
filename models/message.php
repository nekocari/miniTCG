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
    
    protected $id, $date, $sender, $recipient, $text, $status;
    
    private $sender_obj, $recipient_obj;
    
    protected static
        $db_table = 'messages',
        $db_pk = 'id',
        $db_fields = array('id','date','sender','recipient','text','status'),
        $sql_order_by_allowed_values = array('id','date','status','sender','recipient');
    
    private static $accepted_status_values = array('new','all');
    
    
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
        
        $msgs = array();
        
        if(!in_array($status, self::$accepted_status_values)){ 
            $status = self::$accepted_status_values[0]; 
        }
        
        if($status == 'new'){
            return parent::getWhere("recipient = ".intval($id)." AND status = 'new'",['date'=>'DESC']);
        }else{
            return parent::getWhere('recipient = '.intval($id),['date'=>'DESC']);
        }
    }
    
    public static function add($sender, $recipient, $text){
        
        // remove HTML
        $text = strip_tags($text);
        
        $message = new Message();
        $message->setPropValues(['sender'=>$sender, 'recipient'=>$recipient, 'text'=>$text]);
        $message_id = $message->create();
        $message->setPropValues(['id'=>$message_id]);
        
        return $message;
    }
    
    public function read(){
        $this->status = 'read';
        $this->update();
    }
    
    
    public function getId() {
        return $this->id;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getSender() {
        if(!$this->sender_obj instanceof Member){
            $this->sender_obj = Member::getById($this->sender);
            if(is_null($this->sender_obj)){
                $this->sender_obj = new Member();
                $this->sender_obj->setName('System');
            }
        }
        return $this->sender_obj;
    }
    
    public function getRecipient() {
        if(!$this->recipient_obj instanceof Member){
            $this->recipient_obj = Member::getById($this->recipient);
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
    
    public function isNew() {
        $is_new = false;
        if($this->status == 'new'){
            $is_new = true;
        }
        return $is_new;
    }
    
    
}