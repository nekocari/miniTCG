<h1>Nutzerdaten bearbeiten</h1>

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
        		<td><?php echo $var; ?></td>
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