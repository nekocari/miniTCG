<?php
/**
 * 
 * Represents a Shop Card
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'models/setting.php';

class ShopCard extends DbRecordModel {
    
    protected $id, $deck_id, $number, $price, $date, $name;
    
    private $deck_obj;
    
    protected static
        $db_table = 'shop_cards',
        $db_pk = 'id',
        $db_fields = array('id','price','number','deck_id','date','name'),
        $sql_order_by_allowed_values = array('id','deckname','number','date','name');
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getDeck() {
        if(!$this->deck_obj instanceof Carddeck){
            $this->deck_obj = Carddeck::getById($this->deck_id);
        }
        return $this->deck_obj;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getNumber() {
        return $this->number;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function isBuyable() {
        if(Login::loggedIn() and Login::getUser()->getMoney() >= $this->getPrice()){
            return true;
        }else{
            return false;
        }
    }
    
    public function getImageHTML(){
        $card = new Card();
        $card->setPropValues(['deck'=>$this->deck_id,'name'=>$this->getName(),'number'=>$this->getNumber()]);
        return $card->getImageHtml();
    }
    
    public function buy(){
        if(Login::loggedIn() and Login::getUser()->getMoney() >= $this->getPrice()){
            // take money from user
            Login::getUser()->setMoney(Login::getUser()->getMoney() - $this->getPrice());
            Login::getUser()->update();
            // create the card and creat new card entry for owner;
            $card = new Card();
            $card->setPropValues(['owner'=>Login::getUser()->getId(),'deck'=>$this->deck_id,'name'=>$this->getName(),'number'=>$this->getNumber(),'date'=>date('Y-m-d H:i:s')]);
            $card_id = $card->create();
            $card->setPropValues(['id'=>$card_id]);
            // delete from shop
            $this->delete();
            // create log entry
            $log_text = 'Shop -> '.$card->getName().' (#'.$card->getId().') gekauft.';
            Tradelog::addEntry(Login::getUser()->getId(), $log_text);
            // check for levelup
            Login::getUser()->checkLevelUp();
            return true;
        }else{
            return false;
        }
    }
}