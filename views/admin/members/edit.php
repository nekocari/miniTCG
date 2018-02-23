<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="admin/members/list.php">Mitglieder</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Mitglied bearbeiten</h1>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
		<?php foreach($memberdata as $var => $value){ if($var != 'id') {?>
    	<tr>
    		<td><?php echo $var; ?></td>
    		<td><input class="form-control" name="<?php echo $var; ?>" value="<?php echo $value; ?>"></td>
    	</tr>
		<?php } } ?>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="admin/members/list.php">zurück zur Liste</a> 
	&bull; <input class="btn btn-primary" type="submit" name="updateMemberdata" value="speichern">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
</p>
</form>