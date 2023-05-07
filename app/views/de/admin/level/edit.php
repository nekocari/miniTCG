<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('level_index');?>">Level</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Level bearbeiten</h1>

<form enctype="multipart/form-data" method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Level ID</td>
    		<td><input class="form-control" type="text" name="id" value="<?php echo $level->getId(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Level Stufe:</td>
    		<td><input class="form-control" type="number" name="level" value="<?php echo $level->getLevel(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Level Name:</td>
    		<td><input class="form-control" type="text" name="name" value="<?php echo $level->getName(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Benötigte Karten Anzahl:</td>
    		<td><input class="form-control" type="number" name="cards" value="<?php echo $level->getCards(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Level Badge ersetzen?</td>
    		<td><?php echo $level->getLevelBadgeHTML(); ?><br>
    			<input class="form-control" type="file" name="file"></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('level_index');?>">zurück zur Liste</a> 
		&bull; <input class="btn btn-primary" type="submit" name="editLevel" value="speichern">
		<input type="hidden" name="id" value="<?php echo $level->getId(); ?>">
	</p>
</form>