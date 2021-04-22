<h1>Karten Shop</h1>

<p class="text-center">
	Du kannst 
	<span class="h3"><span class="badge badge-light"><?php echo $user_money; ?>  <?php echo $currency_name; ?></span></span> 
	ausgeben
</p>


<?php if(count($s_cards) > 0){ ?>
    <!-- Zeige Karten an wenn vorhanden -->
    
    <form name="shop-cards" class="shop-cards d-flex flex-wrap justify-content-around" method="POST" action="">
    	<?php foreach($s_cards as $s_card){ ?>
        	<div class="card shop-card m-2 text-center">
        		<div class="shop-card-image p-1">
					<?php echo $s_card->getImageHTML(); ?>
        		</div>
        		<div class="p-1">
            		<div class="shop-card-name"><?php echo $s_card->getName(); ?></div>
            		<div class="shop-card-price"><?php echo $s_card->getPrice(); ?> <?php echo $currency_name; ?></div>
            		<div class="shop-card-action">
            			<button class="btn btn-primary btn-sm" value="<?php echo $s_card->getId(); ?>" 
            			name="buy_card" <?php if(!$s_card->isBuyable()){ ?>disabled<?php } ?>>
            				<i class="fas fa-shopping-cart"></i> kaufen
            			</button>
            		</div>
            	</div>
        	</div>
        <?php } ?>
    </form>
    
<?php }else{ ?>
	<!-- Information falls keine Karten vorhanden sind -->
	
	<div class="alert alert-info" role="alert">
		Alle Karten sind <i>ausverkauft!</i> <br>
		Neue Lieferung voraussichtlich am <b><?php echo $shop_next_restock_date; ?></b>
	</div>
	
<?php } ?>