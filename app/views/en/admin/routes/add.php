<?php 
/*
 * variables preset:
 * $route - instance of Route class 
 */
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_routes_index');?>">Routing</a></li>
    <li class="breadcrumb-item active" aria-current="page">create</li>
  </ol>
</nav>

<h1>Create Route</h1>

<form method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Identifier<br><small>unique key (can't be changed)</small></td>
    		<td><input class="form-control" type="text" name="identifier" pattern="^([a-z]|[0-9]|_)+$" required>
    			<small>only lowercase letters without umlauts, numbers, and "_"</small></td>
    	</tr>
    	<tr>
    		<td>URL in browser<br><small>from the app's directory</small></td>
    		<td><input class="form-control" type="text" name="url" required></td>
    	</tr>
    	<tr>
    		<td>Controller Class Name<br><small>without the addition "Controller" (e.g. AdminController = Admin)</small></td>
    		<td><input class="form-control" type="text" name="controller" required></td>
    	</tr>
    	<tr>
    		<td>Method within Class</td>
    		<td><input class="form-control" type="text" name="action" required></td>
    	</tr>
    	<tr>
    		<td>Request Method(s)</td>
    		<td>
    			<select class="form-select" name="method" required>
	    			<option value="get">get</option>
	    			<option value="post">post</option>
	    			<option value="get|post">get und post</option>
	    		</select>
    		</td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_routes_index');?>">go back</a> 
		&bull; <input class="btn btn-primary" type="submit" name="addRoute" value="create">
	</p>
</form>