<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('member_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Mitglieder</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Mitglied bearbeiten</h1>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<?php 
		foreach($memberdata as $var => $value){ 
		     if($var != 'Text' AND $var != 'Status') {
		?>
        	<tr>
        		<td><?php echo $var; ?></td>
        		<td><input type="text" class="form-control" name="<?php echo $var; ?>" value="<?php echo $value; ?>"></td>
        	</tr>
		<?php }elseif($var == 'Status'){ ?>
        	<tr>
        		<td><?php echo $var; ?></td>
        		<td><select class="form-control" name="<?php echo $var; ?>">
        		<?php foreach($accepted_stati as $status){ ?>
        			<option value="<?php echo $status; ?>" <?php if($value == $status){ echo 'selected'; } ?>><?php echo $status; ?></option>
        		<?php } ?>
        		</select></td>
        	</tr>
		<?php }elseif($var == 'Text'){ ?>
        	<tr>
        		<td><?php echo $var; ?></td>
        		<td><textarea class="form-control" name="<?php echo $var; ?>"><?php echo $value; ?></textarea></td>
        	</tr>
		<?php 
		     } 
		} 
		?>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_member_index');?>">zurück zur Liste</a> 
	&bull; <input class="btn btn-primary" type="submit" name="updateMemberdata" value="speichern">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
</p>
</form>

<hr>

<form method="post" action="" onsubmit="return confirm('Diese Aktion kann nicht rückgängig gemacht werden.\nFortfahren?');">
	<p class="text-center">
    	<input class="btn btn-danger" type="submit" name="deleteMemberdata" value="Mitglied endgültig LÖSCHEN">
    	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    </p>
</form>