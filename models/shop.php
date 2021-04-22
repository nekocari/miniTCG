<?php
/**
 * 
 * Represents a Shop
 * 
 * @author Cari
 *
 */
require_once PATH.'models/shop_card.php';
require_once PATH.'models/setting.php';

class Shop {
        
    private $next_restock_date, $shop_restock_minutes, $max_stock, $cards_sum, $card_objs;
    
    public function __construct() {
        $this->next_restock_date = Setting::getByName('shop_next_restock')->getValue();
        $this->shop_restock_minutes = Setting::getByName('shop_restock_minutes')->getValue();
        $this->shop_max_stock = Setting::getByName('shop_max_stock')->getValue();
    }
    
    public function getRestockDate($format='Y-m-d H:i') {
        $datetime = new DateTime($this->next_restock_date);
        return $datetime->format($format);
    }
    
    public function restock() {
        // create datetime objects of last restock and now
        $next_restock_datetime = new DateTime($this->next_restock_date);
        $now_datetime = new DateTime('now');
        
        if($next_restock_datetime <= $now_datetime AND $this->getCardsSum() < $this->shop_max_stock){  
            // add interval to restock date
            $next_date = $now_datetime->add(new DateInterval('PT'.$this->shop_restock_minutes.'M'));
            $this->next_restock_date = $next_date->format('c');
            // save next restock date in settings
            $setting = Setting::getByName('shop_next_restock');
            $setting->setValue($this->next_restock_date);
            $setting->update();
            
            // create random cards
            $new_cards = Card::createRandomCard(null, ($this->shop_max_stock - $this->getCardsSum()) );
            
            // get price settings
            $min_price = Setting::getByName('shop_price_min')->getValue();
            $max_price = Setting::getByName('shop_price_max')->getValue();
            
            // use random cards to add new cards to shop
            foreach($new_cards as $new_card){
                // determin price
                $price = rand($min_price, $max_price);
                // set values for shop card entry
                $sc_values = array('deck_id'=>$new_card['deck'],'number'=>$new_card['number'],'name'=>$new_card['name'],'price'=>$price);
                // create and save shop card
                $new_sc = new ShopCard();
                $new_sc->setPropValues($sc_values);
                $new_sc->create();
            }
            $this->card_objs = null;
            return true;
        }else{
            return false;
        }        
    }
    
    public function getCardsSum() {
        
        if(is_null($this->cards_sum)){
            $this->cards_sum = ShopCard::getCount();
        }
        return $this->cards_sum;
    }
    
    public function getCards($order_settings=null) {
        if(!is_array($this->card_objs)){
            $this->card_objs = ShopCard::getAll($order_settings);
            $this->cards_sum = count($this->card_objs);
        }
        return $this->card_objs;
    }
    
}