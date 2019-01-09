<?php
/**
 * 
 * Represents a Message
 * 
 * @author Cari
 *
 */
require_once PATH.'models/setting.php';
require_once PATH.'models/member.php';

class Message {
    
    private $id;
    private $date;
    private $sender;
    private $recipient;
    private $text;
    private $status;
    private $db;
    
    private static $accepted_status_values = array('new','all');
    
    public function __construct($id, $date, $sender, $recipient, $text, $status) {
        $this->id           = $id;
        $this->date         = $date;
        $this->sender       = $sender;
        $this->recipient    = $recipient;
        $this->text         = $text;
        $this->status       = $status;
        $this->db           = DB::getInstance();
    }
    
    public static function getReceivedByMemberId($id, $status = 'all') {
        try {
            $msgs = array();
            $db = DB::getInstance();
            
            if(!in_array($status, self::$accepted_status_values)){ $status = 'all'; }
            
            if($status != 'all'){
                $req = $db->prepare('SELECT * FROM messages WHERE recipient = :id and status = :status ORDER BY date DESC');
                $req->execute(array(':id'=>$id, ':status'=>$status));
            }else{
                $req = $db->prepare('SELECT * FROM messages WHERE recipient = :id ORDER BY date DESC');
                $req->execute(array(':id'=>$id));
            }
            if($req->rowCount() > 0){
                foreach($req->fetchAll(PDO::FETCH_OBJ) as $msgdata){
                    $sender = Member::getById($msgdata->sender);
                    $recipient = Member::getById($msgdata->recipient);    
                    $msgs[] = new Message($msgdata->id, $msgdata->date, $sender, $recipient, $msgdata->text, $msgdata->status);
                }
            }
            return $msgs;
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public static function add($sender, $recipient, $text){
        try {
        $text = strip_tags($text);
        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO messages (sender,recipient,text) VALUES (:sender,:recipient,:text)');
        $req->execute(array(':sender'=>$sender, ':recipient'=>$recipient,':text'=>$text));
        return true;
        }
        catch (PDOException $pdo_e){
            return 'Einfügen in DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function read($id){
        try{
            $db = Db::getInstance();
            $req = $db->prepare('UPDATE messages SET status = \'read\' WHERE id = :id');
            $req->execute(array(':id'=> $id));
            return true;
        }
        catch (PDOException $pdo_e){
            return 'Update der DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function delete($id){
        try{
            $db = Db::getInstance();
            $req = $db->prepare('DELETE FROM messages WHERE id = :id');
            $req->execute(array(':id'=> $id));
            return true;
        }
        catch (PDOException $pdo_e){
            return 'Löschen aus DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getSender() {
        return $this->sender;
    }
    
    public function getRecipient() {
        return $this->recipient;
    }
    
    public function getText() {
        return $this->text;
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