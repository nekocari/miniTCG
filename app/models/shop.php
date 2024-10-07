<?php
/**
 * 
 * Represents a Shop
 * 
 * @author NekoCari
 *
 */

class Shop {
        
	private $next_restock_date, $shop_restock_minutes, $shop_max_stock, $cards_sum, $card_objs;
    
    public function __construct() {
        $this->next_restock_date = Setting::getByName('shop_next_restock')->getValue();
        $this->shop_restock_minutes = Setting::getByName('shop_restock_minutes')->getValue();
        $this->shop_max_stock = Setting::getByName('shop_max_stock')->getValue();
    }
    
    public function getRestockDate($timezone=DEFAULT_TIMEZONE,$format='d.m.Y H:i') {
        $datetime = new DateTime($this->next_restock_date);
        $datetime->setTimezone(new DateTimeZone($timezone));
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
            
            try{
	            // create random cards
	            $new_cards = Card::createRandomCards(null, ($this->shop_max_stock - $this->getCardsSum()) );
            }
            catch (ErrorException $e){
            	error_log($e->getMessage().PHP_EOL,3,ERROR_LOG);
            }
            
            // get price settings
            $min_price = Setting::getByName('shop_price_min')->getValue();
            $max_price = Setting::getByName('shop_price_max')->getValue();
            
            // use random cards to add new cards to shop
            foreach($new_cards as $new_card){
                // determin price
                $price = rand($min_price, $max_price);
                // set values for shop card entry
                $sc_values = array('deck_id'=>$new_card->getDeckId(),'number'=>$new_card->getNumber(),'name'=>$new_card->getName(),'price'=>$price);
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
    
    public function getCards($login,$order_settings=null) {
        if(!is_array($this->card_objs)){
            $this->card_objs = array();
            $cards = ShopCard::getAll($order_settings);
            if($login->isLoggedIn()){
                foreach($cards as $card){
                    // workaround to user flag method is to remove the id and later add it back in
                    $card_id = $card->getId();
                    $card->setPropValues(['id'=>null]);
                    $card->flag($login->getUser()->getId());
                    $card->setPropValues(['id'=>$card_id]);
                    $this->card_objs[] = $card;
                }
            }
            $this->cards_sum = count($this->card_objs);
        }
        return $this->card_objs;
    }
    
}