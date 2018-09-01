<h4>Deine Anfrage:</h4>

<p>Deine <span class="text-uppercase"><?php echo $offered_card->getName(); ?></span> gegen 
	<span class="text-uppercase"><?php echo $requested_card->getName(); ?></span> von <span class="text-uppercase"><?php echo $requested_card->getOwner()->getName(); ?></span>
</p>

<p class="text-center"><a class="btn btn-dark" href="<?php echo $requested_card->getOwner()->getProfilLink(); ?>">zurück zum Nutzerprofil</a></p>