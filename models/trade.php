<?php
/**
 * 
 * Represents a Trade
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/card.php';
require_once PATH.'models/member.php';
require_once PATH.'models/message.php';

class Trade extends DbRecordModel {
    
    protected $id, $date, $offerer, $offered_card, $recipient, $requested_card, $text, $status;
    private $offerer_obj, $recipient_obj, $offered_card_obj, $requested_card_obj;
    
    protected static
        $db_table = 'trades',
        $db_pk = 'id',
        $db_fields = array('id','date','offerer','offered_card','recipient','requested_card','text','status'),
        $sql_order_by_allowed_values = array('id','status','date','offerer','recipient');
    
    private static $query_status_options = array('new','accepted','declined');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    private static function getTradesByMemberId($member_id,$type, $status='new', $order_by = 'date', $order = 'ASC'){
        $trades = array();
        if(in_array($type,['recieved','sent'])){
            
            if(!in_array($order_by, self::$sql_order_by_allowed_values)){
                $order_by = self::$sql_order_by_allowed_values[0];
                throw new Exception();
            }
            if(!in_array($order, self::$sql_direction_allowed_values)){
                $order = self::$sql_direction_allowed_values[0];
                throw new Exception();
            }
            if(!in_array($status, self::$query_status_options)){
                $status = self::$query_status_options[0];
                throw new Exception();
            }
            
            switch ($type){
                case 'recieved':
                    return parent::getWhere('recipient = '.intval($member_id).' AND status = \''.$status.'\'', [$order_by=>$order]);
                    break;
                case 'sent':
                    return parent::getWhere('offerer = '.intval($member_id).' AND status = \''.$status.'\'', [$order_by=>$order]);
                    break;
            }
            
        }else{
            throw new Exception();
        }
        return $trades;
    }
    
    /**
     * get all trades recieved by a member using the member ID
     *
     * @param int $member_id - id of member
     * @param string $order_by - colum to order by [id|date|status|offerer|recipient]
     * @param string $order - [ASC|DESC]
     *
     * @return boolean|Array(Trade)
     */
    public static function getRecievedByMemberId($member_id, $status='new', $order_by = 'date', $order = 'ASC') {
        
        return self::getTradesByMemberId($member_id, 'recieved');
    }
    
    /**
     * get all trades sent by a member using the member ID
     *
     * @param int $member_id - id of member
     * @param string $order_by - colum to order by [id|date|status|offerer|recipient]
     * @param string $order - [ASC|DESC]
     *
     * @return boolean|Array(Trade)
     */
    public static function getSentByMemberId($member_id, $status='new', $order_by = 'date', $order = 'ASC') {
        
        return self::getTradesByMemberId($member_id, 'sent');
    }
    
    
    public function del() {
        return $this->delete();
    }
    
    public static function add($recipient, $requested_card_id, $offered_card_id, $text) {
        
        
        $offered_card = Card::getById($offered_card_id);
        $requested_card = Card::getById($requested_card_id);        
        $text = strip_tags($text);
        
        if(!is_null($offered_card) AND $offered_card->isTradeable() AND 
            !is_null($requested_card) AND $requested_card->isTradeable() AND 
            $offered_card->getOwner()->getId() == $_SESSION['user']->id AND 
            $requested_card->getOwner()->getID() == $recipient){
            
            $prop_values = array(
                'recipient'     =>$recipient,
                'requested_card'=>$requested_card_id,
                'offered_card'   =>$offered_card_id,
                'offerer'       =>$_SESSION['user']->id,
                'text'          =>$text
            );
                
            $trade = new Trade();
            $trade->setPropValues($prop_values);
            //die(var_dump($trade));
            $trade_id = $trade->create();
            
            if($trade_id !== false){
                return true;
            }
            
        }else{
            throw new Exception('Eine der Karten steht leider nicht mehr zur Verfüngung.');
        }
        
        return false;
    }
    
    public function answer($status, $msg = '') {
        
        if(in_array($status, self::$query_status_options) AND $this->recipient == $_SESSION['user']->id){
            
            $this->setPropValues(['status'=>$status]);
            
            if($this->update()){
            
                switch($status){
                    
                    case 'return_offer':
                        // TBA later
                        break;
                        
                    case 'declined':
                        $msg_text = 'ANFRAGE ABGELEHNT! ['.$this->getRecipient()->getName().']('.$this->getRecipient()->getProfilLink().') tauscht '.
                                strtoupper($this->getRequestedCard()->getName()).' nicht gegen deine '.
                                strtoupper($this->getOfferedCard()->getName()).'.';
                        break;
                }
                
                if(!empty($msg)){
                    $msg_text.= ' Nachricht: "'.strip_tags($msg).'"';
                }
                
                Message::add(Null, $this->getOfferer()->getId(), $msg_text);
                
                return true;
            }
            
        }
        
        return false;
        
    }
    
    
    public function decline($msg = '') {
        
        return $this->answer('declined',$msg);
        
    }
    
    public function accept($msg = '') {
       
        $this->db->beginTransaction();
        if($this->getRecipient()->getId() == $_SESSION['user']->id){
            
            // change owner of offered card to recipient
            if($this->getOfferedCard()->getOwner()->getId() == $this->getOfferer()->getId()){
                
                $this->getOfferedCard()->setOwner($this->getRecipient()->getId());
                $this->getOfferedCard()->setStatus('new');
                $this->getOfferedCard()->store();
                
                // change owner of requested card to offerer
                if($this->getRequestedCard()->getOwner()->getId() == $this->getRecipient()->getId()){
                    
                    $this->getRequestedCard()->setOwner($this->getOfferer()->getId());
                    $this->getRequestedCard()->setStatus('new');
                    $this->getRequestedCard()->store();
                    
                    // update trade status 
                    $req = $this->db->query('UPDATE trades SET status = \'accepted\' WHERE id = '.$this->getId());
                    if($req->rowCount() == 1){
                        
                        // send message to inform offerer
                        $msg_text = 'ANFRAGE ANGENOMMEN! ['.$this->getRecipient()->getName().']('.$this->getRecipient()->getProfilLink().') hat '.
                            strtoupper($this->getRequestedCard()->getName()).' gegen deine '.
                            strtoupper($this->getOfferedCard()->getName()).' getauscht.';
                        
                        if(!empty($msg)){
                            $msg_text.= ' Nachricht: "'.strip_tags($msg).'"';
                        }
                        
                        Message::add(NULL, $this->getOfferer()->getId(), $msg_text);
                        
                        // create Tradelog entry for offerer
                        $text = 'Du hast '.strtoupper($this->getOfferedCard()->getName()).' gegen '.strtoupper($this->getRequestedCard()->getName()).
                            ' von '.$this->getRecipient()->getName().' getauscht.';
                        Tradelog::addEntry($this->getOfferer()->getId(), $text);
                        
                        // create Tradelog entry for recipient
                        $text = 'Du hast '.strtoupper($this->getRequestedCard()->getName()).' gegen '.strtoupper($this->getOfferedCard()->getName()).
                            ' von '.$this->getOfferer()->getName().' getauscht.';
                        Tradelog::addEntry($this->getRecipient()->getId(), $text);
                        
                        
                        $this->db->commit();
                        return true;
                        
                    }else{
                        throw new Exception('Der Status der Tauschanfrage wurde bereits geändert.',5001);
                    }
                }else{
                    throw new Exception('Angefragte Karte ist nicht verfügbar.', 5003);
                }                    
            }else{
                throw new Exception('Angebotene Karte ist nicht verfügbar.', 5004);
            }
        }else{
            throw new Exception('Nutzer und Empfänger stimmen nicht überein.', 5002);
        }
        
        return false;
    }
    
    
    /*
     *  basic GETTER methods 
     */
    
    public function getId() {
        return $this->id;
    }
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    public function getOfferer() {
        if(!$this->offerer_obj instanceof Member){
            $this->offerer_obj = Member::getById($this->offerer);
        }
        return $this->offerer_obj;
    }
    public function getOfferedCard() {
        if(!$this->offered_card_obj instanceof CardCardmanager){
            $this->offered_card_obj = Card::getById($this->offered_card)->getFlaggedCard($this->recipient);
        }
        return $this->offered_card_obj;
    }
    public function getRecipient() {
        if(!$this->recipient_obj instanceof Member){
            $this->recipient_obj = Member::getById($this->recipient);
        }
        return $this->recipient_obj;
    }
    public function getRequestedCard() {
        if(!$this->requested_card_obj instanceof CardCardmanager){
            $this->requested_card_obj = Card::getById($this->requested_card)->getFlaggedCard($this->offerer);
        }
        return $this->requested_card_obj;
    }
    
    public function getStatus() {
        return $this->status;
    }
    public function getText() {
        return $this->text;
    }
    
}