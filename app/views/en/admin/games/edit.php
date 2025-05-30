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
    			<select class="form-select" name="status" required >
    				<option <?php if($game->getStatus() == 'online'){ echo 'selected'; } ?>>online</option>
    				<option <?php if($game->getStatus() == 'offline'){ echo 'selected'; } ?>>offline</option>
    			</select>
    		</td>
    	</tr>
    	<tr>
    		<td>Type</td>
    		<td>
    			<select class="form-select" name="type" id="game-type" required disabled>
    				<option <?php if($game->getType() == 'lucky'){ echo 'selected'; } ?>>lucky</option>
    				<option <?php if($game->getType() == 'custom'){ echo 'selected'; } ?>>custom</option>
    			</select>
    		</td>
    	</tr>
    	<?php if($game->getType() == 'lucky'){ ?>
    	<tr id="lucky-choices">
    		<td>Choices</td>
    		<td><select class="form-select" name="lucky_choice_type">
    				<option value="text" <?php if($choice_type == 'text'){ echo 'selected'; } ?>>Texts</option>
    				<option value="image" <?php if($choice_type == 'image'){ echo 'selected'; } ?>>Images</option>
    			</select>
    			<textarea class="form-control" name="lucky_choices"><?php echo $choices; ?></textarea>
    			<small class="text-muted">Separated by commas.<br>Image paths form app base path. (e.g. public/img/icons/games/img1.gif)</small>
    		</td>
    	</tr>
    	<tr id="lucky-results">
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
    	<?php }elseif($game->getType() == 'custom'){ // C U S T O M  ?>
    	<tr id="custom-method">
    		<td>Use default method?</td>
    		<td>
    			<label for="custom-switch-1">
    				<input id="custom-switch-1" class="custom-checkbox custom-switch" type="radio" name="no_custom_code" value="1" <?php if($game_custom->isUsed()){ echo 'checked'; }; ?>>
    				yes
    			</label>
    			<label for="custom-switch-0">
    				<input id="custom-switch-0" class="custom-checkbox custom-switch" type="radio" name="no_custom_code" value="0" <?php if(!$game_custom->isUsed()){ echo 'checked'; }; ?>>
    				no
    			</label>
    		</td>
    	</tr>
    	<tr id="custom-results" class="custom-game-inputs <?php if(!$game_custom->isUsed()){ echo 'd-none'; }; ?>">
    		<td>Possible Results<br>
    			<small class="text-muted">
    				supported results:
    				<pre>win-card:[NUMBER]<br>win-money:[NUMBER]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="custom_results" placeholder="e.g. won=>win-card:2, lost=>lost"><?php echo $game_custom->getResultsPlain(); ?></textarea>
    			<small class="text-muted">format: [game result send via POST]=>[prize result] use commas to separate multiple result=>prize sets
    			(Read more in the <a href="https://github.com/nekocari/miniTCG/wiki/Games#how-to-add-my-own-game" target="_blank">Wiki</a>)</small>
    		</td>
    	</tr>
    	<tr id="custom-view-file" class="custom-game-inputs <?php if(!$game_custom->isUsed()){ echo 'd-none'; }; ?>">
    		<td>View File<br>
    			<small class="text-muted">file containing the HTML code</small>
    		</td>
    		<td>
    			<div class="input-group">
    				<span class="input-group-text">app/views/(lang)/</span>
    				<input class="form-control" type="text" name="view_file_path" placeholder="game/mygame.php" value="<?php echo $game_custom->getViewFilePath(); ?>">
    			</div>
    		</td>
    	</tr>
    	<tr id="custom-js-file" class="custom-game-inputs <?php if(!$game_custom->isUsed()){ echo 'd-none'; }; ?>">
    		<td>Javascript File<br>
    			<small class="text-muted"><i>optional</i><br>javascript code for game</small>
    		</td>
    		<td>
    			<div class="input-group">
    				<span class="input-group-text">public/js/</span>
    				<input class="form-control" type="text" name="js_file_path" placeholder="mygame.js" value="<?php echo $game_custom->getJsFilePath(); ?>">
    			</div>
    		</td>
    	</tr>
    	<?php } ?>
    	<tr>
    		<td>Routing Identifier</td>
    		<td><input class="form-control" type="text" name="route_identifier" value="<?php echo $game->getRouteIdentifier(); ?>" required>
    		<small class="text-muted">For "lucky" games you can use <pre class="d-inline">game_default_lucky</pre>.<br>
    			For "custom" games <pre class="d-inline">game_custom</pre> (Read more in the <a href="https://github.com/nekocari/miniTCG/wiki/Games#how-to-add-my-own-game" target="_blank">Wiki</a>)</small></td>
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

<script src="public/js/add_game.js"  charset="utf-8"></script>