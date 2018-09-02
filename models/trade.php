<?php
/**
 * 
 * Represents a Trade
 * 
 * @author Cari
 *
 */
require_once PATH.'models/setting.php';
class Trade {
    
    private $id;
    private $date;
    private $offerer;
    private $recipient;
    private $offered_card;
    private $requested_card;
    private $text;
    private $status;
    private static $query_order_by_options = array('id','date','status','offerer','recipient');
    private static $query_order_direction_options = array('ASC','DESC');
    private static $query_status_options = array('new','accepted','declined');
    
    public function __construct($id, $date, $offerer, $offered_card, $recipient, $requested_card, $status, $text) {
        $this->id           = $id;
        $this->date         = $date;
        $this->offerer      = $offerer;
        $this->offered_card = $offered_card;
        $this->recipient    = $recipient;
        $this->requested_card = $requested_card;
        $this->status       = $status;
        $this->text         = $text;
        $this->db           = DB::getInstance();
    }
    
    
    public static function getById($id) {
        try {
            require_once PATH.'models/card.php';
            require_once PATH.'models/member.php';
            
            $db = DB::getInstance();
            
            $req = $db->prepare('SELECT * FROM trades WHERE id = :id');
            $req->execute(array(':id'=>$id));
            $tradedata = $req->fetch(PDO::FETCH_OBJ);
            
            $offerer = Member::getById($tradedata->offerer);
            $recipient = Member::getById($tradedata->recipient);
            $offered_card = Card::getById($tradedata->offered_card);
            $requested_card = Card::getById($tradedata->requested_card);
            
            return new Trade($tradedata->id, $tradedata->date, $offerer, $offered_card, $recipient, $requested_card, $tradedata->status, $tradedata->text);
        }
        catch (Exception $e){
            return $e->getMessage();
        }
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
        
        $db = DB::getInstance();
        require_once PATH.'models/member.php';
        require_once PATH.'models/member.php';
        
        $trades = array();
        
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'date';
        }
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'DESC';
        }
        if(!in_array($status, self::$query_status_options)){
            $status = 'new';
        }
        
        $req = $db->prepare('SELECT * FROM trades WHERE recipient = :member AND status = :status ORDER BY '.$order_by.' '.$order);
        $req->execute(array(':member'=>$member_id, ':status'=>$status));
        
        foreach($req->fetchAll(PDO::FETCH_ASSOC) as $trade){
            $offerer = Member::getById($trade['offerer']);
            $recipient = Member::getById($trade['recipient']);
            $offered_card = Card::getById($trade['offered_card']);
            $requested_card = Card::getById($trade['requested_card']);
            $trades[] = new Trade($trade['id'], $trade['date'], $offerer, $offered_card, $recipient, $requested_card, $trade['status'], $trade['text']);
        }
        
        return $trades;
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
        
        $db = DB::getInstance();
        require_once PATH.'models/member.php';
        require_once PATH.'models/member.php';
        
        $trades = array();
        
        if(!in_array($order_by, self::$query_order_by_options)){
            $order_by = 'date';
        }
        if(!in_array($order, self::$query_order_direction_options)){
            $order = 'DESC';
        }
        if(!in_array($status, self::$query_status_options)){
            $status = 'new';
        }
        
        $req = $db->prepare('SELECT * FROM trades WHERE offerer = :member AND status = :status ORDER BY '.$order_by.' '.$order);
        $req->execute(array(':member'=>$member_id, ':status'=>$status));
        
        foreach($req->fetchAll(PDO::FETCH_ASSOC) as $trade){
            $offerer = Member::getById($trade['offerer']);
            $recipient = Member::getById($trade['recipient']);
            $offered_card = Card::getById($trade['offered_card']);
            $requested_card = Card::getById($trade['requested_card']);
            $trades[] = new Trade($trade['id'], $trade['date'], $offerer, $offered_card, $recipient, $requested_card, $trade['status'], $trade['text']);
        }
        
        return $trades;
    }
    
    
    public static function delete($id) {
        $db = DB::getInstance();
        try {
            if(isset($_SESSION['user'])){
                $req = $db->prepare('DELETE FROM trades WHERE id = :id AND offerer = :offerer');
                $req->execute(array(':id'=>$id, ':offerer'=>$_SESSION['user']->id));
                if($req->rowCount() > 0){
                    return true;
                }else{
                    throw new Exception('Tausch und Nutzer ID Kombination existiert nicht!');
                }
            }else{
                throw new Exception('Du bist nicht eingeloggt!');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function del() {
        return self::delete($this->id);
    }
    
    public static function add($recipient, $requested_card_id, $offered_card_id, $text) {
        // TODO: check if cards are still free
        $db = DB::getInstance();
        
        $offered_card = Card::getById($offered_card_id);
        $requested_card = Card::getById($requested_card_id);        
        $text = strip_tags($text);
        
        if($offered_card->isTradeable() AND $requested_card->isTradeable() AND 
            $offered_card->getOwner()->getId() == $_SESSION['user']->id AND $requested_card->getOwner()->getID() == $recipient){
            
            $req = $db->prepare('INSERT INTO trades (offerer,recipient,offered_card,requested_card,text) VALUES (:offerer,:recipient,:offered_card,:requested_card,:text) ');
            $req->execute(array(':offerer'=>$_SESSION['user']->id, ':recipient'=>$recipient, ':offered_card'=>$offered_card_id, ':requested_card'=>$requested_card_id, ':text'=>$text ));
            
            return true;
            
        }else{
            return false;
        }
    }
    
    public function decline($msg = '') {
        try {
            if($this->recipient->getId() == $_SESSION['user']->id){
                // update trade status to declined
                $req = $this->db->query('UPDATE trades SET status = \'declined\' WHERE id = '.$this->id); 
                if($req->rowCount() == 1){
                    // send message to inform offerer
                    require_once PATH.'models/message.php';
                    $msg_text = 'ANFRAGE ABGELEHNT! '.$this->recipient->getName().' tauscht '.strtoupper($this->requested_card->getName()).' nicht gegen deine '.strtoupper($this->offered_card->getName()).'.';
                    if(!empty($msg)){
                        $msg_text.= ' Nachricht: "'.strip_tags($msg).'"';
                    }
                    Message::add($this->recipient->getId(), $this->offerer->getId(), $msg_text);
                    return true;
                }else{
                    throw new Exception('Der Status der Tauschanfrage wurde bereits geändert.');
                }
            }else{
                throw new Exception('Du kannst diese Anfrage nicht ablehnen, da du nicht der Empfänger bist!');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function accept($msg = '') {
        try {
            $this->db->beginTransaction();
            if($this->recipient->getId() == $_SESSION['user']->id){
                
                // change owner of offered card to recipient
                if($this->offered_card->getOwner()->getId() == $this->offerer->getId()){
                    
                    $this->offered_card->setOwner($this->recipient->getId());
                    $this->offered_card->setStatus('new');
                    $this->offered_card->store();
                    
                    // change owner of requested card to offerer
                    if($this->requested_card->getOwner()->getId() == $this->recipient->getId()){
                        
                        $this->requested_card->setOwner($this->offerer->getId());
                        $this->requested_card->setStatus('new');
                        $this->requested_card->store();
                        
                        // update trade status to declined
                        $req = $this->db->query('UPDATE trades SET status = \'accepted\' WHERE id = '.$this->id);
                        if($req->rowCount() == 1){
                            
                            // send message to inform offerer
                            require_once PATH.'models/message.php';
                            $msg_text = 'ANFRAGE ANGENOMMEN! '.$this->recipient->getName().' hat '.strtoupper($this->requested_card->getName()).' gegen deine '.strtoupper($this->offered_card->getName()).' getauscht.';
                            if(!empty($msg)){
                                $msg_text.= ' Nachricht: "'.strip_tags($msg).'"';
                            }
                            Message::add($this->recipient->getId(), $this->offerer->getId(), $msg_text);
                            
                            // create Tradelog entry for offerer
                            $text = 'Du hast '.strtoupper($this->offered_card->getName()).' gegen '.strtoupper($this->requested_card->getName()).' von '.$this->recipient->getName().' getauscht.';
                            Tradelog::addEntry($this->offerer->getId(), $text);
                            // create Tradelog entry for recipient
                            $text = 'Du hast '.strtoupper($this->requested_card->getName()).' gegen '.strtoupper($this->offered_card->getName()).' von '.$this->offerer->getName().' getauscht.';
                            Tradelog::addEntry($this->recipient->getId(), $text);
                            $text = null;
                            
                            $this->db->commit();
                            return true;
                            
                        }else{
                            throw new Exception('Der Status der Tauschanfrage wurde bereits geändert.');
                        }
                    }else{
                        throw new Exception('Angefragte Karte ist nicht verfügbar.');
                    }                    
                }else{
                    throw new Exception('Angebotene Karte ist nicht verfügbar.');
                }
            }else{
                throw new Exception('Du kannst diese Anfrage nicht annehmen, da du nicht der Empfänger bist!');
            }
        }
        catch (Exception $e) {
            $this->db->rollBack();
            return $e->getMessage();
        }
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
        return $this->offerer;
    }
    public function getOfferedCard() {
        return $this->offered_card;
    }
    public function getRecipient() {
        return $this->recipient;
    }
    public function getRequestedCard() {
        return $this->requested_card;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getText() {
        return $this->text;
    }
    
}