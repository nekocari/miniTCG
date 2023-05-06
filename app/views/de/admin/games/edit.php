<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_games_index');?>">Spiele</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Spiel bearbeiten</h1>

<form method="post" action="<?php echo Routes::getUri('admin_games_edit').'?id='.$game->getId();?>">
    <table class="table table-striped">
    	<tr>
    		<td>Schlüsselwort</td>
    		<td><input class="form-control" type="text" name="game_key" value="<?php echo $game->getKey(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Name</td>
    		<td>
    			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){?>
    				<span class="badge"><?php echo $lang; ?></span>
    				<input class="form-control mb-1" type="text" name="name[<?php echo $key; ?>]" value="<?php echo $game->getName($key); ?>" required>
    			<?php } ?>
    		</td>
    	</tr>
    	<tr>
    		<td>Beschreibung</td>
    		<td>
    			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){?>
    				<span class="badge"><?php echo $lang; ?></span>
    				<textarea class="form-control mb-1" name="description[<?php echo $key; ?>]"  required><?php echo $game->getDescription($key); ?></textarea>
    			<?php } ?>
    		</td>
    	</tr>
    	<tr>
    		<td>Status</td>
    		<td>
    			<select class="form-control" name="status" required >
    				<option <?php if($game->getStatus() == 'online'){ echo 'selected'; } ?>>online</option>
    				<option <?php if($game->getStatus() == 'offline'){ echo 'selected'; } ?>>offline</option>
    			</select>
    		</td>
    	</tr>
    	<tr>
    		<td>Art</td>
    		<td>
    			<select class="form-control" name="type" id="game-type" required disabled>
    				<option <?php if($game->getType() == 'lucky'){ echo 'selected'; } ?>>lucky</option>
    				<option <?php if($game->getType() == 'custom'){ echo 'selected'; } ?>>custom</option>
    			</select>
    		</td>
    	</tr>
    	<tr id="lucky-choices" class="<?php if($game->getType() != 'lucky'){ echo 'd-none'; } ?>">
    		<td>Auswahlmöglichkeiten</td>
    		<td><select class="form-control" name="lucky_choice_type">
    				<option value="text" <?php if($choice_type == 'text'){ echo 'selected'; } ?>>Texte</option>
    				<option value="image" <?php if($choice_type == 'image'){ echo 'selected'; } ?>>Bildpfade</option>
    			</select>
    			<textarea class="form-control" name="lucky_choices"><?php echo $choices; ?></textarea>
    			<small class="text-muted">Jeweils mit Komma trennen.<br>Bildpfade bitte ausgehend vom Basispfad. (z.B. public/img/icons/games/img1.gif)</small>
    		</td>
    	</tr>
    	<tr id="lucky-results" class="<?php if($game->getType() != 'lucky'){ echo 'd-none'; } ?>">
    		<td>Mögliche Resultate<br>
    			<small class="text-muted">
    				unterstützte Resultate:
    				<pre>win-card:[ZAHL]<br>win-money:[ZAHL]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="lucky_results"><?php echo $results; ?></textarea>
    			<small class="text-muted">Jeweils mit Komma trennen.<br>Es müssen <b>mindestens</b> so viele Resultate wie Auswahlmöglichkeiten festgelegt werden!</small>
    		</td>
    	</tr>
    	<tr>
    		<td>Routing Identifier</td>
    		<td><input class="form-control" type="text" name="route_identifier" value="<?php echo $game->getRouteIdentifier(); ?>" required>
    		<small class="text-muted">Für "lucky" Spiele kannst du <pre class="d-inline">game_default_lucky</pre> verwenden.</small></td>
    	</tr>
    	<tr>
    		<td>Tägliches Spiel?</td>
    		<td>
    			<label for="daily-1">
    				<input id="daily-1" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="1" <?php if($game->isDailyGame()){ echo "checked"; } ?>>
    				ja
    			</label>
    			<label for="daily-0">
    				<input id="daily-0" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="0" <?php if(!$game->isDailyGame()){ echo "checked"; } ?>> 
    				nein
    			</label>
    		</td>
    	</tr>
    	<tr id="wait-time-input" class="<?php if($game->isDailyGame()){ echo "d-none"; } ?>">
    		<td>Wartezeit<br><small class="text-muted">in Minuten</small></td>
    		<td><input class="form-control" type="number" name="wait_time" value="<?php echo $game->getWaitTime(); ?>"></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_games_index');?>">zurück zur Liste</a> 
		&bull; <input class="btn btn-primary" type="submit" name="editGame" value="speichern">
		<input type="hidden" name="id" value="<?php echo $game->getId(); ?>">
	</p>
</form>

<script>
let isDaily = false;
let dailySwitches = document.querySelectorAll('input[name="daily_game"]');
let waitTimeInput = document.getElementById('wait-time-input');

dailySwitches.forEach((checkbox) => { 
	checkbox.addEventListener('change', (event) => {
		if (event.target.checked) {
			changeInputVisibility(event.target.value);
		}
	})
});
function changeInputVisibility(val){ 
	if(val == 1){
		waitTimeInput.classList.add('d-none');
	}else{
		waitTimeInput.classList.remove('d-none');
	}	
}
let luckyChoices = document.getElementById('lucky-choices');
let luckyResults = document.getElementById('lucky-results');
let gameType = document.getElementById('game-type');
gameType.addEventListener("change", choices);
function choices(){ 
	if(gameType.value != 'lucky'){
		luckyChoices.classList.add('d-none');
		luckyResults.classList.add('d-none');
	}else{
		luckyChoices.classList.remove('d-none');
		luckyResults.classList.remove('d-none');
	}	
	console.log(gameType.value);
}
</script>