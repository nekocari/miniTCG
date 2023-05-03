<h1>Member Data</h1>

<h2>Profil Data</h2>
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
        			<small>You can use <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
					to format your text!</small>
        		</td>
        	</tr>
		<?php 
		     
		}elseif($var == 'Mail'){
		    ?>
        	<tr>
        		<td><?php echo $var; ?><br><small class="text-muted">not public</small></td>
        		<td><input type="email" class="form-control" name="<?php echo $var; ?>" value="<?php echo $value; ?>" required></td>
        	</tr>
		<?php 
		     } 
		} 
		?>
	</tbody>
</table>

<p class="text-center mx-2">
	<input class="btn btn-primary" type="submit" name="updateMemberdata" value="save">
</p>
</form>



<h1>Settings</h1>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<tr>
			<td>Timezone</td>
			<td>
				<select class="form-control" name="timezone" required>
					<?php foreach(DateTimeZone::listIdentifiers() as $zone){ ?>
					<option <?php if($zone == $member->getTimezone()){ echo 'selected'; } ?>><?php echo $zone; ?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Language</td>
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
	<input class="btn btn-primary" type="submit" name="updateSettings" value="save">
</p>
</form>



<h1>Change Password</h1>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<tr>
			<td>new password</td>
			<td><input class="form-control" type="password" name="password1" required></td>
		</tr>
		<tr>
			<td>repeat</td>
			<td><input class="form-control" type="password" name="password2" required></td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<input class="btn btn-primary" type="submit" name="changePassword" value="save">
</p>
</form>