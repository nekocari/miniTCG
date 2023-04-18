<?php
/*
 * Shop Controller
 * 
 * @author NekoCari
 */

class ShopController extends AppController {
    
    /**
     * index
     */
    public function shop() {
        
    	$this->redirectNotLoggedIn();
    	$shop = new Shop();
        
        $data = array();  
        
        if(isset($_POST['buy_card'])){
            $selected_card = ShopCard::getByPk($_POST['buy_card']);
            if($selected_card instanceof ShopCard) {
                    try{
                    	if($selected_card->buy($this->login())){
                        	$this->layout()->addSystemMessage('success','cardshop_card_bought',[],'<br><div class="text-center">'.$selected_card->getImageHTML().'</div>');
                    	}else{
                    		$this->layout()->addSystemMessage('error','cardshop_no_money');
                    	}
                    }
                    catch(Exception $e){
                        $this->layout()->addSystemMessage('error','cardshop_buy_error',[],$e->getMessage());
                    }
            }else{
                $this->layout()->addSystemMessage('error','cardshop_card_gone');
            }
        }
        
        $shop->restock();
              
        $data['s_cards'] = $shop->getCards($this->login(),['name'=>'ASC']);
        $data['shop_next_restock_date'] = $shop->getRestockDate();
        $data['currency_name'] = Setting::getByName('currency_name')->getValue();
        $data['currency_icon'] = Setting::getByName('currency_icon_path')->getValue();
        $data['user_money'] = $this->login()->getUser()->getMoney();
        
        $this->layout()->render('shop/shop.php',$data);
        
    }
    
    
    
}
?>