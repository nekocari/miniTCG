<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_games_index');?>">Games</a></li>
    <li class="breadcrumb-item active" aria-current="page">add</li>
  </ol>
</nav>

<h1>Add Game</h1>

<form method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Keyword</td>
    		<td><input class="form-control" type="text" name="game_key" pattern="[a-z0-9_\-]+" required>
    		<small class="text-muted">allowed characters: lower case letters, numbers as well as "-" and "_"</small></td>
    	</tr>
    	<tr>
    		<td>Name</td>
    		<td>
    			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){?>
    				<span class="badge text-bg-light"><?php echo $lang; ?></span>
    				<input class="form-control mb-1" type="text" name="name[<?php echo $key; ?>]" required>
    			<?php } ?>
    		</td>
    	</tr>
    	<tr>
    		<td>Description</td>
    		<td>
    			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){?>
    				<span class="badge text-bg-light"><?php echo $lang; ?></span>
    				<textarea class="form-control mb-1" name="description[<?php echo $key; ?>]" required></textarea>
    			<?php } ?>
    		</td>
    	</tr>
    	<tr>
    		<td>Status</td>
    		<td>
    			<select class="form-select" name="status" required >
    				<option selected>online</option>
    				<option>offline</option>
    			</select>
    		</td>
    	</tr>
    	<tr>
    		<td>Type</td>
    		<td>
    			<select class="form-select" name="type" id="game-type" required >
    				<option selected>lucky</option>
    				<option>custom</option>
    			</select>
    		</td>
    	</tr>
    	<tr id="lucky-choices" class="">
    		<td>Choices</td>
    		<td><select class="form-select" name="lucky_choice_type">
    				<option value="text">Texts</option>
    				<option value="image">Images</option>
    			</select>
    			<textarea class="form-control" name="lucky_choices" placeholder="e.g. up, down, left, right"></textarea>
    			<small class="text-muted">Separated by commas.<br>Image paths form app base path. (e.g. public/img/icons/games/img1.gif)</small>
    		</td>
    	</tr>
    	<tr id="lucky-results" class="">
    		<td>Possible Results<br>
    			<small class="text-muted">
    				supported results:
    				<pre>win-card:[NUMBER]<br>win-money:[NUMBER]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="lucky_results" placeholder="e.g. win-card:2, win-money:100, lost, lost"></textarea>
    			<small class="text-muted">Separated by commas.<br>There have to be <b>at least</b> as many results as there are choices!</small>
    		</td>
    	</tr>
    	<tr id="custom-method" class="d-none">
    		<td>Use default method?</td>
    		<td>
    			<label for="custom-switch-1">
    				<input id="custom-switch-1" class="custom-checkbox custom-switch" type="radio" name="no_custom_code" value="1" checked>
    				yes
    			</label>
    			<label for="custom-switch-0">
    				<input id="custom-switch-0" class="custom-checkbox custom-switch" type="radio" name="no_custom_code" value="0"> 
    				no
    			</label>
    		</td>
    	</tr>
    	<tr id="custom-results" class="custom-game-inputs d-none">
    		<td>Possible Results<br>
    			<small class="text-muted">
    				supported results:
    				<pre>win-card:[NUMBER]<br>win-money:[NUMBER]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="custom_results" placeholder="e.g. won=>win-card:2, lost=>lost"></textarea>
    			<small class="text-muted">format: [game result send via POST]=>[prize result] use commas to separate multiple result=>prize sets
    			(Read more in the <a href="https://github.com/nekocari/miniTCG/wiki/Games#how-to-add-my-own-game" target="_blank">Wiki</a>)</small>
    		</td>
    	</tr>
    	<tr id="custom-view-file" class="custom-game-inputs d-none">
    		<td>View File<br>
    			<small class="text-muted">file containing the HTML code</small>
    		</td>
    		<td>
    			<div class="input-group">
    				<span class="input-group-text">app/views/(lang)/</span>
    				<input class="form-control" type="text" name="view_file_path" placeholder="game/mygame.php">
    			</div>
    		</td>
    	</tr>
    	<tr id="custom-js-file" class="custom-game-inputs d-none">
    		<td>Javascript File<br>
    			<small class="text-muted"><i>optional</i><br>javascript code for game</small>
    		</td>
    		<td>
    			<div class="input-group">
    				<span class="input-group-text">public/js/</span>
    				<input class="form-control" type="text" name="js_file_path" placeholder="mygame.js">
    			</div>
    		</td>
    	</tr>
    	<tr>
    		<td>Routing Identifier</td>
    		<td><input class="form-control" type="text" name="route_identifier" required>
    		<small class="text-muted">For "lucky" games you can use <pre class="d-inline">game_default_lucky</pre>.<br>
    			For "custom" games <pre class="d-inline">game_custom</pre> (Read more in the <a href="https://github.com/nekocari/miniTCG/wiki/Games#how-to-add-my-own-game" target="_blank">Wiki</a>)</small></td>
    	</tr>
    	<tr>
    		<td>Is daily game?</td>
    		<td>
    			<label for="daily-1">
    				<input id="daily-1" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="1">
    				yes
    			</label>
    			<label for="daily-0">
    				<input id="daily-0" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="0" checked> 
    				no
    			</label>
    		</td>
    	</tr>
    	<tr id="wait-time-input">
    		<td>Wait Time<br><small class="text-muted">in minutes</small></td>
    		<td><input class="form-control" type="number" name="wait_time" value="1" required></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_games_index');?>">go back</a> 
		&bull; <input class="btn btn-primary" type="submit" name="addGame" value="save">
	</p>
</form>

<script src="public/js/add_game.js"  charset="utf-8"></script>