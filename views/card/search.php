<h1>Tauschpartner finden</h1>
<h2>Gesuchte Karte</h2>

<form method="post" action="">
	<table class="table table-striped">
		<colgroup>
			<col width="30%">
			<col width="70%">
		</colgroup>
		
		<tr>
			<td>Deck</td>
			<td>
				<select name="deck_id" class="form-control">
					<?php foreach($decks as $deck){ ?>
						<option value="<?php echo $deck->getId(); ?>"><?php echo $deck->getDeckname(); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Nummer</td>
			<td>
				<select name="number" class="form-control">
					<?php for($i = 1; $i <= $decksize; $i++){ ?>
						<option><?php echo $i; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</table>
	
	<div class="text-center">
		<input class="btn btn-primary" type="submit" name="search" value="suchen">
	</div>
</form>