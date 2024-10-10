<h1>Tauschpartner finden</h1>
<h2>Gesuchte Karte</h2>

<form method="get" action="">
	<table class="table table-striped">
		<colgroup>
			<col width="30%">
			<col width="70%">
		</colgroup>
		
		<tr>
			<td>Deck</td>
			<td>
				<select class="form-select" id="deck-select" name="deck_id">
					<?php foreach($decks as $deck){ ?>
						<option value="<?php echo $deck->getId(); ?>" data-size="<?php echo $deck->getSize(); ?>">
							<?php echo $deck->getDeckname(); ?>
						</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Nummer</td>
			<td>
				<select class="form-select" id="number-select" name="number">
					<?php for($i = 1; $i <= $decksize; $i++){ ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</table>
	
	<div class="text-center">
		<input class="btn btn-primary" type="submit" name="search" value="suchen">
	</div>
</form>

<script>	
let numberSelect = document.getElementById('number-select');
let deckSelect = document.getElementById('deck-select');
deckSelect.addEventListener("change", updateSelectOptions);
function updateSelectOptions(){ 
	let selected = deckSelect.selectedOptions;
	if(selected.length == 1){
    	let deckSize = selected[0].getAttribute('data-size');
	    for (a in numberSelect.options) { numberSelect.options.remove(0); }
	    for (i=1;i<=deckSize;i++){
	    	option = document.createElement('option');
	    	option.value = i;
	    	option.text = i;
	    	numberSelect.add(option);
	    }
	}else{
		console.log('more than one selected option found for #deck-select'); 
	}
}
</script>