<h1>Tauschanfrage erstellen</h1>

<form method="POST" action="">
<div class="row">

	<div class="col-12 col-md-6">
    	<h4>ausgewählte Karte</h4>
		<div class="text-center">
			<p><?php echo $requested_card->getImageHTML(); ?><br>
			<span class="text-uppercase"><?php echo $requested_card->getName(); ?></span><br>
			von <a href="<?php echo $requested_card->getOwner()->getProfilLink(); ?>"><?php echo $requested_card->getOwner()->getName(); ?></a></p>
		</div>
	</div>

	<div class="col-12 col-md-6">
		<h4>Karte anbieten</h4>
		<div class="text-center">
			<p><select class="form-control">
                	<option value="">Karte auswählen</option>
                    <?php foreach($cards as $card){ ?>
                    <option value="<?php echo $card->getId(); ?>"><?php echo $card->getName(); ?></option>
                    <?php } ?>
                </select>
            </p>
            <p><textarea class="form-control" name="text" maxlength="250"
            	placeholder="Nachricht (optional)"></textarea><p>
        </div>
	</div>
	
</div>


<p class="text-center">
	<a class="btn btn-dark" href="<?php echo $requested_card->getOwner()->getProfilLink(); ?>">zurück zum Nutzerprofil</a> &bull; 
	<input class="btn btn-primary" type="submit" name="add_trade" value="Angebot senden">
	<input type="hidden" name="requested_card_id" value="<?php echo $requested_card->getId(); ?>">
</p>

</form>