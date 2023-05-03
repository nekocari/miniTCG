<h1>Trade Offers</h1>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link" href="<?php echo Routes::getUri('trades_recieved'); ?>">recieved</a></li>
	<li class="nav-item"><a class="nav-link active">sent</a></li>
</ul>

<?php foreach($trades as $trade){ ?>
<div class="card my-4">
	<div class="card-header text-muted text-right font-italic">
		<small> <a class="font-weight-bold" href="<?php echo $trade->getRecipient()->getProfilLink(); ?>"><?php echo $trade->getRecipient()->getName(); ?></a> 
			on <?php echo $trade->getDate($this->login->getUser()->getTimezone()); ?></small>
	</div>
	<div class="card-body text-center">
		<div>
		
			<div class="text-center d-inline-block">
				<div class="d-inline-block card-member-profil">
					<?php echo $trade->getOfferedCard()->getImageHTML(); ?>
    			</div>
			</div>
			<i class="fas fa-sync-alt h1"></i>
			<div class="text-center d-inline-block">
				<div class="d-inline-block card-member-profil <?php 
				if($trade->getRequestedCard()->missingInKeep() AND !$trade->getRequestedCard()->owned()){ echo " card-missing-keep"; }
				if($trade->getRequestedCard()->missingInCollect()AND !$trade->getRequestedCard()->owned()){ echo " card-missing-collect"; }
				if($trade->getRequestedCard()->onWishlist()AND !$trade->getRequestedCard()->owned()){ echo " card-missing-wishlist"; } 
    				if($trade->getRequestedCard()->mastered()){ echo " card-mastered"; } ?>">
    				<?php echo $trade->getRequestedCard()->getImageHTML(); ?>
    			</div>
			</div>
		</div>
		<div>Your <span class="text-uppercase"><?php echo $trade->getOfferedCard()->getName(); ?></span> for 
			<span class="text-uppercase"><?php echo $trade->getRequestedCard()->getName(); ?></span> from <?php echo $trade->getRecipient()->getName(); ?></div>
		<div class="text-muted font-italic">Message: "<?php echo $trade->getText(); ?>"</div>
	</div>
	<div class="card-footer text-center">
		<form class="m-0" method="post" action="">
			<button class="btn btn-sm btn-danger" name="delete"><i class="fas fa-times"></i> delete</button>
			<input type="hidden" name="id" value="<?php echo $trade->getId(); ?>">
		</form>
	</div>
</div>
<?php } ?>


<div class="my-4"><?php if(count($trades) == 0){ $this->renderMessage('info','no trade offers sent'); } ?></div>


<?php include $this->partial('templates/card_color_legend.php'); ?>