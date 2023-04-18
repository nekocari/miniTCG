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
        $db_fields = array('id','price','number','deck_id','date','utc','name'),
        $sql_order_by_allowed_values = array('id','deckname','number','date','name');
    
    public function __construct() {
        parent::__construct();
        $this->deck = $this->deck_id;
    }
        
    public function getDate($timezone = DEFAULT_TIMEZONE) {
    	$date = new DateTime($this->utc);
    	$date->setTimezone(new DateTimeZone($timezone));
        return $date->format(Setting::getByName('date_format')->getValue());
    }
    
    public function getPrice() {
        return $this->price;
    }
       
    public function isBuyable($login) {
        if($login instanceof Login AND $login->isLoggedIn() and $login->getUser()->getMoney() >= $this->getPrice()){
            return true;
        }else{
            return false;
        }
    }
    
    public function buy($login){
    	if($login instanceof Login AND $login->isloggedIn() and $login->getUser()->getMoney() >= $this->getPrice()){
	        try{
	            // take money from user
	            $login->getUser()->setMoney($login->getUser()->getMoney() - $this->getPrice());
	            $login->getUser()->update();
	            // create the card and creat new card entry for owner;
	            $card = Card::createNewCard($login->getUser(), $this->getDeckId(), $this->getNumber(), $this->getName());
	            // delete from shop
	            $this->delete();
	            // create log entry
	            $log_text = $card->getName().' (#'.$card->getId().')';
	            Tradelog::addEntry($login->getUser(), 'cardshop_buy_log_text', $log_text);
	            // check for levelup
	            $login->getUser()->checkLevelUp();
            	return true;
	        }
	        catch(ErrorException $e){
	        	die($e->getMessage());
	        }
        }else{
            return false;
        }
    }
}