<h1>Card Manager</h1>

<p class="text-center">
<?php foreach(Card::getAcceptedStatiObj() as $status){ ?>
	<a class="btn btn-outline-dark" href="<?php echo Routes::getUri('member_cardmanager')."?status=".$status->getId(); ?>"><?php echo strtoupper($status->getName()); ?></a>
<?php } ?>
</p>

<h2>Category: <?php echo strtoupper($cardmanager->getStatus()->getName()); ?></h2>

<form name="sortCards" method="POST" action="">
	
	<?php echo $pagination; ?>
	
	<div>
	<?php foreach($cards as $card){ ?>
		<div class="d-inline-block text-center m-1 card-cardmanager <?php if($card->missingInKeep()){ echo " card-missing-keep"; } 
		   if($card->onWishlist()){ echo " card-missing-wishlist"; } 
		   if($card->missingInCollect()){ echo " card-missing-collect"; } if($card->mastered()){ echo " card-mastered"; } ?>">
			<div>
				<?php echo $card->getImageHtml(); ?>
			</div>
			<div>
				<small><?php echo $card->getName(); ?></small>
			</div>
			<div>
	        	<select class="form-control form-control-sm" name="newStatus[<?php echo $card->getId(); ?>]">
	        		<?php echo $card->getSortingOptionsHTML(); ?>
	        	</select>
	    	</div>
		</div>
	<?php } ?>
	</div>
	
	<?php echo $pagination; ?>
	
	<?php 
	if(count($cards) == 0){ 
		$this->renderMessage('info','No cards in this category.'); 
	}else{ ?>
		<p class="text-center"><button class="btn btn-primary" role="submit" name="changeCardStatus" value="1">sort cards</button></p>
	<?php } ?>
	
	
	<input type="hidden" name="action" value="move_cards">
</form>

<?php include $this->partial('templates/card_color_legend.php'); ?>