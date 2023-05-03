<h1>Trade Offers</h1>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link active">recieved</a></li>
	<li class="nav-item"><a class="nav-link" href="<?php echo Routes::getUri('trades_sent'); ?>">sent</a></li>
</ul>

<?php foreach($trades as $trade){ ?>
<div class="card my-4">
	<div class="card-header text-muted text-right font-italic">
		<small> <a class="font-weight-bold" href="<?php echo $trade->getOfferer()->getProfilLink(); ?>"><?php echo $trade->getOfferer()->getName(); ?></a> 
			on <?php echo $trade->getDate($this->login->getUser()->getTimezone()); ?></small>
	</div>
	<div class="card-body text-center">
		<div>
			<div class="text-center d-inline-block">
				<div class="d-inline-block card-member-profil">
    				<?php echo $trade->getRequestedCard()->getImageHTML(); ?>
                </div>
            </div>
			<i class="fas fa-sync-alt h1"></i>
			<div class="text-center d-inline-block">
				<div class="d-inline-block card-member-profil <?php 
				if($trade->getOfferedCard()->missingInKeep() AND !$trade->getOfferedCard()->owned()){ echo " card-missing-keep"; } 
				if($trade->getOfferedCard()->missingInCollect() AND !$trade->getOfferedCard()->owned()){ echo " card-missing-collect"; }
				if($trade->getOfferedCard()->onWishlist()AND !$trade->getOfferedCard()->owned()){ echo " card-missing-wishlist"; } 
    				if($trade->getOfferedCard()->mastered()){ echo " card-mastered"; } ?>">
    				<?php echo $trade->getOfferedCard()->getImageHTML(); ?>
                </div>
            </div>
		</div>
		<div>
			Your <span class="text-uppercase"><?php echo $trade->getRequestedCard()->getName(); ?></span> for 
			<span class="text-uppercase"><?php echo $trade->getOfferedCard()->getName(); ?></span> from <?php echo $trade->getOfferer()->getName(); ?>
		</div>
			
		<div class="text-muted font-italic">Message: "<?php echo $trade->getText(); ?>"</div>
	</div>
	<div class="card-footer text-center">
		<form class="m-0 p-0" method="POST" action="">
			<input type="text" class="form-control my-1" maxlength="250" name="text" placeholder="Message (optional)">
    		<button class="btn btn-sm btn-primary" name="accept"><i class="fas fa-check"></i> yes</button>
    		&bull; 
    		<button class="btn btn-sm btn-danger" name="decline"><i class="fas fa-times"></i> no</button>
    		<input type="hidden" name="id" value="<?php echo $trade->getId(); ?>">
    	</form>
	</div>
</div>
<?php } ?>

<div class="my-4"><?php if(count($trades) == 0){ $this->renderMessage('info','no trade offers recieved'); } ?></div>


<?php include $this->partial('templates/card_color_legend.php'); ?>