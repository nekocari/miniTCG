<h4>Your offer:</h4>

<p>Your <span class="text-uppercase"><?php echo $offered_card->getName(); ?></span> for 
	<span class="text-uppercase"><?php echo $requested_card->getName(); ?></span> from <span class="text-uppercase"><?php echo $requested_card->getOwner()->getName(); ?></span>
</p>

<p class="text-center"><a class="btn btn-dark" href="<?php echo $requested_card->getOwner()->getProfilLink(); ?>">back to member</a></p>