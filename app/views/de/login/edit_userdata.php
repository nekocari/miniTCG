<h1>Nutzerdaten</h1>

<h2>Profildaten</h2>
<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<?php 
		foreach($memberdata as $var => $value){ 
		     if($var != 'Text' AND $var !='Mail') {
		?>
        	<tr>
        		<td><?php echo $var; ?></td>
        		<td><input type="text" class="form-control" name="<?php echo $var; ?>" value="<?php echo $value; ?>"></td>
        	</tr>
		<?php 
		     }elseif($var == 'Text'){
		     ?>
        	<tr>
        		<td><?php echo $var; ?></td>
        		<td>
        			<textarea class="form-control" name="<?php echo $var; ?>"><?php echo $value; ?></textarea>
        			<small>Du kannst <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
					verwenden um den Text zu formatieren!</small>
        		</td>
        	</tr>
		<?php 
		     
		}elseif($var == 'Mail'){
		    ?>
        	<tr>
        		<td><?php echo $var; ?><br><small class="text-muted">nicht öffentlich</small></td>
        		<td><input type="email" class="form-control" name="<?php echo $var; ?>" value="<?php echo $value; ?>" required></td>
        	</tr>
		<?php 
		     } 
		} 
		?>
	</tbody>
</table>

<p class="text-center mx-2">
	<input class="btn btn-primary" type="submit" name="updateMemberdata" value="speichern">
</p>
</form>



<h1>Einstellungen</h1>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<tr>
			<td>Zeitzone</td>
			<td>
				<select class="form-control" name="timezone" required>
					<?php foreach(DateTimeZone::listIdentifiers() as $zone){ ?>
					<option <?php if($zone == $member->getTimezone()){ echo 'selected'; } ?>><?php echo $zone; ?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Sprache</td>
			<td>
				<select class="form-control" name="lang" required>
					<?php foreach(SUPPORTED_LANGUAGES as $key=>$lang){ ?>
					<option value="<?php echo $key; ?>" <?php if($key == $member->getLang()){ echo 'selected'; } ?>><?php echo $lang; ?></option>
					<?php }?>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<input class="btn btn-primary" type="submit" name="updateSettings" value="speichern">
</p>
</form>



<h1>Passwort ändern</h1>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<tr>
			<td>neues Passwort</td>
			<td><input class="form-control" type="password" name="password1" required></td>
		</tr>
		<tr>
			<td>Wiederholung</td>
			<td><input class="form-control" type="password" name="password2" required></td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<input class="btn btn-primary" type="submit" name="changePassword" value="speichern">
</p>
</form>