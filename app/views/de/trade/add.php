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
			<img class="cardimage">
		</div>
		
		<div class="text-center m-2">
			<select class="form-control" id="offered-card-id" name="offered_card_id">
            	<option value="">Karte auswählen</option>            	
                <?php foreach($cards as $group => $g_cards){ if(count($g_cards)){ ?>
            	<option value="">- - - - </option>
                <?php } foreach($g_cards as $card){ ?>
                <option value="<?php echo $card->getId(); ?>" data-url="<?php echo $card->getImageUrl(); ?>">
                	<?php if($card->missingInCollect() AND !$card->owned()){ echo '&star; '; } ?>
                	<?php if($card->missingInKeep() AND !$card->owned()){ echo '&#11048; '; } ?>
                	<?php if($card->onWishlist() AND !$card->owned()){ echo '&#9825; '; } ?>
                	<?php if($card->mastered()){ echo '&#10680; '; } ?>
                	<?php echo $card->getName().' ('.$card->getPossessionCounter().')'; ?>
                </option>
                <?php } } ?>
            </select>   
        	<small>&star;&nbsp;Sammlung / &#11048;&nbsp;behalten / &#9825;&nbsp;Wunschliste / &#10680;&nbsp;gemastert</small>
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
    	//offeredCard[0].style.backgroundImage = "url('"+imgUrl+"')";
    	offeredCard[0].src = imgUrl;
    	
	}else{
		console.log('more than one selected option found for #offered_card'); 
	}
}
</script>