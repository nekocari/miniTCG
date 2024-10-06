<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_games_index');?>">Spiele</a></li>
    <li class="breadcrumb-item active" aria-current="page">anlegen</li>
  </ol>
</nav>

<h1>Spiel anlegen</h1>

<form method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Schlüsselwort</td>
    		<td><input class="form-control" type="text" name="game_key" pattern="[a-z0-9_\-]+" required>
    		<small class="text-muted">erlaubte Zeichen: Kleinbuchstaben, Zahlen sowie "-" und "_"</small></td>
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
    		<td>Beschreibung</td>
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
    		<td>Art</td>
    		<td>
    			<select class="form-select" name="type" id="game-type" required >
    				<option selected>lucky</option>
    				<option>custom</option>
    			</select>
    		</td>
    	</tr>
    	<tr id="lucky-choices" class="">
    		<td>Auswahlmöglichkeiten</td>
    		<td><select class="form-select" name="lucky_choice_type">
    				<option value="text">Texte</option>
    				<option value="image">Bildpfade</option>
    			</select>
    			<textarea class="form-control" name="lucky_choices" placeholder="z.B. oben, unten, links, rechts"></textarea>
    			<small class="text-muted">Jeweils mit Komma trennen.<br>Bildpfade bitte ausgehend vom Basispfad. (z.B. public/img/icons/games/img1.gif)</small>
    		</td>
    	</tr>
    	<tr id="lucky-results" class="">
    		<td>Mögliche Resultate<br>
    			<small class="text-muted">
    				unterstützte Resultate:
    				<pre>win-card:[ZAHL]<br>win-money:[ZAHL]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="lucky_results" placeholder="z.B. win-card:2, win-money:100, lost, lost"></textarea>
    			<small class="text-muted">Jeweils mit Komma trennen.<br>Es müssen <b>mindestens</b> so viele Resultate wie Auswahlmöglichkeiten festgelegt werden!</small>
    		</td>
    	</tr>
    	<tr id="custom-method" class="d-none">
    		<td>Standard Methode verwenden?</td>
    		<td>
    			<label for="custom-switch-1">
    				<input id="custom-switch-1" class="custom-checkbox custom-switch" type="radio" name="no_custom_code" value="1" checked>
    				ja
    			</label>
    			<label for="custom-switch-0">
    				<input id="custom-switch-0" class="custom-checkbox custom-switch" type="radio" name="no_custom_code" value="0"> 
    				nein
    			</label>
    		</td>
    	</tr>
    	<tr id="custom-results" class="custom-game-inputs d-none">
    		<td>Mögliche Resultate<br>
    			<small class="text-muted">
    				unterstützte Resultate:
    				<pre>win-card:[ZAHL]<br>win-money:[ZAHL]<br>lost</pre>
    			</small>
    		</td>
    		<td>
    			<textarea class="form-control" name="custom_results" placeholder="z.B. won=>win-card:2, lost=>lost"></textarea>
    			<small class="text-muted">Format: [Spielergebnis wie durch POST übergeben]=>[Resultat] mehrere mögliche Ergebnisse durch Komma trennen
    			(Mehr dazu im <a href="https://github.com/nekocari/miniTCG/wiki/Games#how-to-add-my-own-game" target="_blank">Wiki</a>)</small>
    		</td>
    	</tr>
    	<tr id="custom-view-file" class="custom-game-inputs d-none">
    		<td>View Datei<br>
    			<small class="text-muted">Datei die den HTML Code beinhaltet.</small>
    		</td>
    		<td>
    			<div class="input-group">
    				<span class="input-group-text">app/views/(lang)/</span>
    				<input class="form-control" type="text" name="view_file_path" placeholder="game/mygame.php">
    			</div>
    		</td>
    	</tr>
    	<tr id="custom-js-file" class="custom-game-inputs d-none">
    		<td>Javascript Datei<br>
    			<small class="text-muted"><i>optional</i><br>Javascript Code für Spiel</small>
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
    			<small class="text-muted">Für "lucky" Spiele kannst du <pre class="d-inline">game_default_lucky</pre> verwenden.<br>
    			Für "custom" Spiele <pre class="d-inline">game_custom</pre> (Mehr dazu im <a href="https://github.com/nekocari/miniTCG/wiki/Games#how-to-add-my-own-game" target="_blank">Wiki</a>)</small>
    		</td>
    	</tr>
    	<tr>
    		<td>Tägliches Spiel?</td>
    		<td>
    			<label for="daily-1">
    				<input id="daily-1" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="1">
    				ja
    			</label>
    			<label for="daily-0">
    				<input id="daily-0" class="custom-checkbox daily-switch" type="radio" name="daily_game" value="0" checked> 
    				nein
    			</label>
    		</td>
    	</tr>
    	<tr id="wait-time-input">
    		<td>Wartezeit<br><small class="text-muted">in Minuten</small></td>
    		<td><input class="form-control" type="number" name="wait_time" value="1" required></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_games_index');?>">zurück zur Liste</a> 
		&bull; <input class="btn btn-primary" type="submit" name="addGame" value="speichern">
	</p>
</form>

<script src="public/js/add_game.js"  charset="utf-8"></script>