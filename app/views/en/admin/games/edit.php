<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_games_index');?>">Games</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Game</h1>

<form method="post" action="<?php echo Routes::getUri('admin_games_edit').'?id='.$game->getId();?>">
    <table class="table table-striped">
    	<tr>
    		<td>Keyword</td>
    		<td><input class="form-control" type="text" name="game_key" value="<?php echo $game->getKey(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Name</td>
    		<td>
    			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){?>
    				<span class="badge text-bg-light"><?php echo $lang; ?></span>
    				<input class="form-control mb-1" type="text" name="name[<?php echo $key; ?>]" value="<?php echo $game->getName($key); ?>" required>
    			<?php } ?>
    		</td>
    	</tr>
    	<tr>
    		<td>Description</td>
    		<td>
    			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){?>
    				<span class="badge text-bg-light"><?php echo $lang; ?></span>
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
    		<td>Type</td>
    		<td>
    			<select class="form-control" name="type" id="game-type" required disabled>
    				<option <?php if($game->getType() == 'lucky'){ echo 'selected'; } ?>>lucky</option>
    				<option <?php if($game->getType() == 'custom'){ echo 'selected'; } ?>>custom</option>
    			</select>
    		</td>
    	</tr>
    	<tr id="lucky-choices" class="<?php if($game->getType() != 'lucky'){ echo 'd-none'; } ?>">
    		<td>Choices</td>
    		<td><select class="form-control" name="lucky_choice_type">
    				<option value="text" <?php if($choice_type == 'text'){ echo 'selected'; } ?>>Texts</option>
    				<option value="image" <?php if($choice_type == 'image'){ echo 'selected'; } ?>>Images</option>
    			</select>
    			<textarea class="form-control" name="lucky_choices"><?php echo $choices; ?></textarea>
    			<small class="text-muted">Separated by commas.<br>Image paths form app base path. (e.g. public/img/icons/games/img1.gif)</small>
    		</td>
    	</tr>
    	<tr id="lucky-results" class="<?php if($game->getType() != 'lucky'){ echo 'd-none'; } ?>">
    		<td>Possible Results<br>
    			<small class="text-muted">
    				supported results:
    				<pre>win-card:[NUMBER]<br>win-money:[NUMBER]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="lucky_results"><?php echo $results; ?></textarea>
    			<small class="text-muted">Separated by commas.<br>There have to be <b>at least</b> as many results as there are choices!</small>
    		</td>
    	</tr>
    	<tr>
    		<td>Routing Identifier</td>
    		<td><input class="form-control" type="text" name="route_identifier" value="<?php echo $game->getRouteIdentifier(); ?>" required>
    		<small class="text-muted">For "lucky" games you can use <pre class="d-inline">game_default_lucky</pre>.</small></td>
    	</tr>
    	<tr>
    		<td>Is daily game?</td>
    		<td>
    			<label for="daily-1">
    				<input id="daily-1" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="1" <?php if($game->isDailyGame()){ echo "checked"; } ?>>
    				yes
    			</label>
    			<label for="daily-0">
    				<input id="daily-0" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="0" <?php if(!$game->isDailyGame()){ echo "checked"; } ?>> 
    				no
    			</label>
    		</td>
    	</tr>
    	<tr id="wait-time-input" class="<?php if($game->isDailyGame()){ echo "d-none"; } ?>">
    		<td>Wait Time<br><small class="text-muted">in minutes</small></td>
    		<td><input class="form-control" type="number" name="wait_time" value="<?php echo $game->getWaitTime(); ?>"></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_games_index');?>">go back</a> 
		&bull; <input class="btn btn-primary" type="submit" name="editGame" value="save">
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