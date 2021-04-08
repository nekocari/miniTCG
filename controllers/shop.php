<?php
/*
 * Shop Controller
 */

require_once 'models/shop.php';
require_once 'models/shop_card.php';

class ShopController {
    
    /**
     * index
     */
    public function shop() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $data = array();  
        
        if(isset($_POST['buy_card'])){
            $selected_card = ShopCard::getByPk($_POST['buy_card']);
            if(!is_null($selected_card)) {
                if($selected_card->isBuyable()){
                    try{
                        $selected_card->buy();
                        $data['_success'][] = SystemMessages::getSystemMessageText('cardshop_card_bought').'<br><div class="text-center">'.$selected_card->getImageHTML().'</div>';
                    }
                    catch(Exception $e){
                        $data['_error'][] = SystemMessages::getSystemMessageText('cardshop_buy_error').$e->getMessage();
                    }
                }else{
                    $data['_error'][] = SystemMessages::getSystemMessageText('cardshop_no_money');
                }
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('cardshop_card_gone');
            }
        }
        
        $shop = new Shop();
        $shop->restock();
              
        $data['s_cards'] = $shop->getCards(['name'=>'ASC']);
        $data['shop_next_restock_date'] = $shop->getRestockDate();
        $data['currency_name'] = Setting::getByName('currency_name')->getValue();
        $data['currency_icon'] = Setting::getByName('currency_icon_path')->getValue();
        $data['user_money'] = Login::getUser()->getMoney();
        
        Layout::render('shop/shop.php',$data);
        
    }
    
    
    
}
?>