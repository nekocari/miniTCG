<?php
/**
 * 
 * Represents a Trade
 * 
 * @author NekoCari
 *
 */

class Trade extends DbRecordModel {
    
    protected $id, $date, $utc, $offerer_id, $offered_card, $recipient_id, $requested_card, $text, $status;
    private $offerer_obj, $recipient_obj, $offered_card_obj, $requested_card_obj;
    
    protected static
        $db_table = 'trades',
        $db_pk = 'id',
        $db_fields = array('id','date','utc','offerer_id','offered_card','recipient_id','requested_card','text','status'),
        $sql_order_by_allowed_values = array('id','status','date','offerer_id','recipient_id');
    
    private static $query_status_options = array('new','accepted','declined');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 
     * @param unknown $id
     * @return Trade|NULL
     */
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
                	return parent::getWhere(['recipient_id'=>$member_id,'status'=>$status],[$order_by=>$order]);
                    break;
                case 'sent':
                	return parent::getWhere(['offerer_id'=>$member_id,'status'=>$status],[$order_by=>$order]);
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
     *
     * @return boolean|Array(Trade)
     */
    public static function getRecievedByMemberId($member_id, $status='new') {
        
        return self::getTradesByMemberId($member_id, 'recieved', $status);
    }
    
    /**
     * get all trades sent by a member using the member ID
     *
     * @param int $member_id - id of member
     *
     * @return boolean|Array(Trade)
     */
    public static function getSentByMemberId($member_id, $status='new') {
        
        return self::getTradesByMemberId($member_id, 'sent', $status);
    }
    
    /**
     * @deprecated use delete() instead
     * @return boolean
     */
    public function del() {
        return $this->delete();
    }
    
    public static function add($recipient, $offerer, $requested_card_id, $offered_card_id, $text) {
        
        
        $offered_card = Card::getById($offered_card_id);
        $requested_card = Card::getById($requested_card_id);        
        $text = strip_tags($text);
        
        if(!is_null($offered_card) AND $offered_card->isTradeable() AND 
            !is_null($requested_card) AND $requested_card->isTradeable() AND 
            $offered_card->getOwner()->getId() == $offerer AND 
            $requested_card->getOwner()->getID() == $recipient){
            
            $prop_values = array(
                'recipient_id'     =>$recipient,
                'requested_card'=>$requested_card_id,
                'offered_card'   =>$offered_card_id,
                'offerer_id'       =>$offerer,
                'text'          =>$text
            );
                
            $trade = new Trade();
            $trade->setPropValues($prop_values);
            //die(var_dump($trade));
            if($trade->create()){
                return true;
            }
            
        }else{
            throw new Exception('card_no_longer_available');
        }
        
        return false;
    }
    
    public function answer($login,$status, $msg = '') {
        
        if(in_array($status, self::$query_status_options) AND $this->recipient_id == $login->getUser()->getId()){
            
            $this->setPropValues(['status'=>$status]);
            
            if($this->update()){
            
                switch($status){
                    
                    case 'return_offer':
                        // TBA later
                        break;
                        
                    case 'declined':
                    	$search = ['{{MEMBER_NAME_RECIPIENT}}','{{MEMBER_LINK_RECIPIENT}}','{{REQUESTED_CARD_NAME}}','{{OFFERED_CARD_NAME}}'];
                    	$replace = [$this->getRecipient()->getName(),$this->getRecipient()->getProfilLink(),$this->getRequestedCard()->getName(),$this->getOfferedCard()->getName()];
                    	$msg_text = str_replace($search, $replace, SystemMessageTextHandler::getInstance()->getTextByCode('trade_declined_msg_text',$this->getOfferer()->getLang()));
                        break;
                }
                
                if(!empty($msg)){
                	$msg_text.= '  "'.strip_tags($msg).'"';
                }
                
                Message::add(Null, $this->getOfferer()->getId(), $msg_text);
                
                return true;
            }
            
        }
        
        return false;
        
    }
    
    
    public function decline($login,$msg = '') {
        
        return $this->answer($login,'declined',$msg);
        
    }
    
    public function accept($login,$msg = '') {
       
        $this->db->beginTransaction();
        if($this->getRecipient()->getId() == $login->getUser()->getId()){
            
            // change owner of offered card to recipient
            if($this->getOfferedCard()->getOwner()->getId() == $this->getOfferer()->getId()){
            	$new_status_id = CardStatus::getNew()->getId();
                
                $this->getOfferedCard()->setOwner($this->getRecipient()->getId());
                $this->getOfferedCard()->setStatusId($new_status_id);
                $this->getOfferedCard()->store();
                
                // change owner of requested card to offerer
                if($this->getRequestedCard()->getOwner()->getId() == $this->getRecipient()->getId()){
                    
                    $this->getRequestedCard()->setOwner($this->getOfferer()->getId());
                    $this->getRequestedCard()->setStatusId($new_status_id);
                    $this->getRequestedCard()->store();
                    
                    // update trade status 
                    $req = $this->db->query('UPDATE trades SET status = \'accepted\' WHERE id = '.$this->getId());
                    if($req->rowCount() == 1){
                        
                    	// send message to inform offerer
                    	$search = ['{{MEMBER_NAME_RECIPIENT}}','{{MEMBER_LINK_RECIPIENT}}','{{REQUESTED_CARD_NAME}}','{{OFFERED_CARD_NAME}}'];
                    	$replace = [$this->getRecipient()->getName(),$this->getRecipient()->getProfilLink(),$this->getRequestedCard()->getName(),$this->getOfferedCard()->getName()];
                    	$msg_text = str_replace($search, $replace, SystemMessageTextHandler::getInstance()->getTextByCode('trade_accepted_msg_text',$this->getOfferer()->getLang()));
                    	if(!empty($msg)){
                            $msg_text.= '  "'.strip_tags($msg).'"';
                        }
                        
                        Message::add(NULL, $this->getOfferer()->getId(), $msg_text);
                        
                        // create Tradelog entry for offerer
                        Tradelog::addEntry($this->getOfferer(), 'trade_accepted_log_text', $this->getRecipient()->getName().': '.strtoupper($this->getOfferedCard()->getName()).' <-> '.strtoupper($this->getRequestedCard()->getName()));
                        
                        // create Tradelog entry for recipient
                        Tradelog::addEntry($this->getRecipient(), 'trade_accepted_log_text', $this->getOfferer()->getName().': '.strtoupper($this->getRequestedCard()->getName()).' <-> '.strtoupper($this->getOfferedCard()->getName()));
                        
                        
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
    public function getDate($timezone = DEFAULT_TIMEZONE) {
    	$date = new DateTime($this->utc);
    	$date->setTimezone(new DateTimeZone($timezone));
        return $date->format(Setting::getByName('date_format')->getValue().' H:i');
    }
    public function getOfferer() {
        if(!$this->offerer_obj instanceof Member){
            $this->offerer_obj = Member::getById($this->offerer_id);
        }
        return $this->offerer_obj;
    }
    public function getOfferedCard() {
        if(!$this->offered_card_obj instanceof CardFlagged){
            $card = new CardFlagged();
            $card->setPropValues(['id'=>$this->offered_card]);
            $this->offered_card_obj = $card->flag($this->recipient_id);
        }
        return $this->offered_card_obj;
    }
    public function getRecipient() {
        if(!$this->recipient_obj instanceof Member){
            $this->recipient_obj = Member::getById($this->recipient_id);
        }
        return $this->recipient_obj;
    }
    public function getRequestedCard() {
        if(!$this->requested_card_obj instanceof CardFlagged){
            $card = new CardFlagged();
            $card->setPropValues(['id'=>$this->requested_card]);
            $this->requested_card_obj = $card->flag($this->offerer_id);
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