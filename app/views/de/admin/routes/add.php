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
    <li class="breadcrumb-item active" aria-current="page">anlegen</li>
  </ol>
</nav>

<h1>Route anlegen</h1>

<form method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Identifier<br><small>einzigartiger Schlüssel (unveränderbar)</small></td>
    		<td><input class="form-control" type="text" name="identifier" pattern="^([a-z]|[0-9]|_)+$" required>
    			<small>nur Kleinbuchstaben ohne Umlaute, Zahlen, sowie "_"</small></td>
    	</tr>
    	<tr>
    		<td>URL im Browser<br><small>ausgehend vom Basisverzeichnis der App</small></td>
    		<td><input class="form-control" type="text" name="url" required></td>
    	</tr>
    	<tr>
    		<td>Controller Klassen Name<br><small>ohne Zusatz "Controller" (bsp. AdminController = Admin)</small></td>
    		<td><input class="form-control" type="text" name="controller" required></td>
    	</tr>
    	<tr>
    		<td>Methode innerhalb der Klasse</td>
    		<td><input class="form-control" type="text" name="action" required></td>
    	</tr>
    	<tr>
    		<td>Anfragenmethode(n)</td>
    		<td>
    			<select class="form-control" name="method" required>
	    			<option value="get">get</option>
	    			<option value="post">post</option>
	    			<option value="get|post">get und post</option>
	    		</select>
    		</td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_routes_index');?>">zurück zur Liste</a> 
		&bull; <input class="btn btn-primary" type="submit" name="addRoute" value="anlegen">
	</p>
</form>