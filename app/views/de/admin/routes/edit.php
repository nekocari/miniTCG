<?php 
/*
 * variables preset:
 * $route - instance of Route class 
 */
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_routes_index');?>">Routing</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Route bearbeiten</h1>

<?php if(!$route->isDeletable()){ 
	$this->renderMessage('info','<b>V O R S I C H T !</b><br>Es handelt sich um einen Standard Eintrag. Änderungen könnten schwerwiegende Probleme in der App verursachen.<br>Bitte nimm nur Änderungen vor, wenn du dir deiner Sache zu 100% sicher bist.');
} ?>

<form method="post" action="<?php echo Routes::getUri('admin_routes_edit')."?id=".$route->getIdentifier(); ?>">
    <table class="table table-striped">
    	<tr>
    		<td>Identifier<br><small>einzigartiger Schlüssel (unveränderbar)</small></td>
    		<td><input class="form-control" type="text" name="idf" pattern="^([a-z]|[0-9]|_)+$" value="<?php echo $route->getIdentifier(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>URL im Browser<br><small>ausgehend vom Basisverzeichnis der App</small></td>
    		<td><input class="form-control" type="text" name="url" value="<?php echo $route->getUrl(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Controller Klassen Name<br><small>ohne Zusatz "Controller" (bsp. AdminController = Admin)</small></td>
    		<td><input class="form-control" type="text" name="controller" value="<?php echo $route->getController(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Methode innerhalb der Klasse</td>
    		<td><input class="form-control" type="text" name="action" value="<?php echo $route->getAction(); ?>"required></td>
    	</tr>
    	<tr>
    		<td>Anfragenmethode(n)</td>
    		<td>
    			<select class="form-control" name="method" required>
	    			<option value="get" <?php if($route->getMethod() == 'get'){ echo 'selected'; } ?>>get</option>
	    			<option value="post" <?php if($route->getMethod() == 'post'){ echo 'selected'; } ?>>post</option>
	    			<option value="get|post" <?php if($route->getMethod() == 'get|post'){ echo 'selected'; } ?>>get und post</option>
	    		</select>
    		</td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_routes_index');?>">zurück zur Liste</a> 
		&bull; <input class="btn btn-primary" type="submit" name="editRoute" value="speichern">
		<input type="hidden" name="identifier" value="<?php echo $route->getIdentifier(); ?>">
	</p>
</form>