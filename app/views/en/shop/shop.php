<h1>Card Shop</h1>

<p class="text-center">
	You got
	<span class="h3"><span class="badge badge-light"><?php echo $user_money; ?>  <?php echo $currency_name; ?></span></span> 
	to spend.
</p>


<?php if(count($s_cards) > 0){ ?>
    <!-- show cards -->
    
    <form name="shop-cards" class="shop-cards d-flex flex-wrap justify-content-around" method="POST" action="">
    	<?php foreach($s_cards as $s_card){ ?>
        	<div class="card shop-card m-2 text-center">
            	<div class="d-inline-block mb-0 shop-card-image <?php 
            	if($s_card->missingInKeep() AND !$s_card->owned()){ echo " card-missing-keep"; }
            	if($s_card->missingInCollect() AND !$s_card->owned()){ echo " card-missing-collect"; }
            	if($s_card->onWishlist() AND !$s_card->owned()){ echo " card-missing-wishlist"; } 
            	if($s_card->mastered()){ echo " card-mastered"; } ?>">
					<?php echo $s_card->getImageHTML(); ?>
        		</div>
        		<div class="p-1">
            		<div class="shop-card-name"><?php echo $s_card->getName(); ?></div>
            		<div class="shop-card-price"><?php echo $s_card->getPrice(); ?> <?php echo $currency_name; ?></div>
            		<div class="shop-card-action">
            			<button class="btn btn-primary btn-sm" value="<?php echo $s_card->getId(); ?>" 
            			name="buy_card" <?php if(!$s_card->isBuyable($this->login)){ ?>disabled<?php } ?>>
            				<i class="fas fa-shopping-cart"></i> buy
            			</button>
            		</div>
            	</div>
        	</div>
        <?php } ?>
    </form>

	<?php include $this->partial('templates/card_color_legend.php'); ?>

<!-- Info if no cards are in stock -->
<?php }else{ $this->renderMessage('info','We are <i>sold out!</i> <br> Next delivery: <b>'.$shop_next_restock_date.'</b>'); } ?>