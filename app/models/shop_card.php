<?php
/**
 * 
 * Represents a Shop Card
 * 
 * @author NekoCari
 *
 */

class ShopCard extends CardFlagged {
    
    /*
    protected $id, $deck_id, $number, $price, $date, $name;
    
    private $deck_obj;
    */
    protected $price, $deck_id;
    
    protected static
        $db_table = 'shop_cards',
        $db_pk = 'id',
        $db_fields = array('id','price','number','deck_id','date','name'),
        $sql_order_by_allowed_values = array('id','deckname','number','date','name');
    
    public function __construct() {
        parent::__construct();
        $this->deck = $this->deck_id;
    }
        
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getPrice() {
        return $this->price;
    }
       
    public function isBuyable() {
        if(Login::loggedIn() and $this->login()->getUser()->getMoney() >= $this->getPrice()){
            return true;
        }else{
            return false;
        }
    }
    
    public function buy(){
        if(Login::loggedIn() and $this->login()->getUser()->getMoney() >= $this->getPrice()){
            // take money from user
            $this->login()->getUser()->setMoney($this->login()->getUser()->getMoney() - $this->getPrice());
            $this->login()->getUser()->update();
            // create the card and creat new card entry for owner;
            $card = new Card();
            $card->setPropValues(['owner'=>$this->login()->getUser()->getId(),'deck'=>$this->deck_id,'name'=>$this->getName(),'number'=>$this->getNumber(),'date'=>date('Y-m-d H:i:s')]);
            $card_id = $card->create();
            $card->setPropValues(['id'=>$card_id]);
            // delete from shop
            $this->delete();
            // create log entry
            $log_text = $card->getName().' (#'.$card->getId().')';
            Tradelog::addEntry($this->login()->getUser()->getId(), 'cardshop_buy_log_text', $log_text);
            // check for levelup
            $this->login()->getUser()->checkLevelUp();
            return true;
        }else{
            return false;
        }
    }
}