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
    private $status;
    private static $query_order_by_options = array('id','date','status','offerer','recipient');
    private static $query_order_direction_options = array('ASC','DESC');
    private static $query_status_options = array('new','accepted','declined');
    
    public function __construct($id, $date, $offerer, $offered_card, $recipient, $requested_card, $status) {
        $this->id           = $id;
        $this->date         = $date;
        $this->offerer      = $offerer;
        $this->offered_card = $offered_card;
        $this->recipient    = $recipient;
        $this->requested_card = $requested_card;
        $this->status       = $status;
        $this->db           = DB::getInstance();
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
            $trades[] = new Trade($trade['id'], $trade['date'], $offerer, $offered_card, $recipient, $requested_card, $trade['status']);
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
            $trades[] = new Trade($trade['id'], $trade['date'], $offerer, $offered_card, $recipient, $requested_card, $trade['status']);
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
    
}