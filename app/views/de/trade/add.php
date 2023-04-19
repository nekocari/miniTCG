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
		
		<div class="text-center" id="offered-card">
			<?php echo $searchcardurl; ?>
		</div>
		
		<div class="text-center m-2">
			<select class="form-control" id="offered-card-id" name="offered_card_id">
            	<option value="">Karte auswählen</option>
                <?php foreach($cards as $card){ ?>
                <option value="<?php echo $card->getId(); ?>" data-url="<?php echo $card->getImageUrl(); ?>">
                	<?php echo $card->getName().' ('.$card->getPossessionCounter().')'; ?>
                	<?php if($card->missingInKeep() AND !$card->owned()){ echo ' [KEEP]'; } ?>
                	<?php if($card->missingInCollect() AND !$card->owned()){ echo ' [COLLECT]'; } ?>
                	<?php if($card->mastered()){ echo ' [masterd]'; } ?>
                </option>
                <?php } ?>
            </select>   
        </div>
	</div>
	
	<div class="col-12 my-2 px-4">
        <textarea class="form-control" name="text" maxlength="250" placeholder="Nachricht (optional)"></textarea>
	</div>
	
</div>


<p class="text-center">
	<a class="btn btn-dark" href="<?php echo $requested_card->getOwner()->getProfilLink(); ?>">zurück zum Nutzerprofil</a> &bull; 
	<input class="btn btn-primary" type="submit" name="add_trade" value="Angebot senden">
	<input type="hidden" name="requested_card_id" value="<?php echo $requested_card->getId(); ?>">
	<input type="hidden" name="recipient" value="<?php echo $requested_card->getOwner()->getId(); ?>">
</p>

</form>

<script>
let offerSelect = document.getElementById('offered-card-id');
offerSelect.addEventListener("change", changeImage);
function changeImage(){ 
	let selected = offerSelect.selectedOptions;
	if(selected.length == 1){
    	let imgUrl = selected[0].getAttribute('data-url');
    	let offeredCard = document.getElementById('offered-card').getElementsByClassName('cardimage');
    	offeredCard[0].style.backgroundImage = "url('"+imgUrl+"')";
    	
	}else{
		console.log('more than one selected option found for #offered_card'); 
	}
}
</script>