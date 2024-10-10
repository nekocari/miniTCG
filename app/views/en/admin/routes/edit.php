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
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Route</h1>

<?php if(!$route->isDeletable()){ 
	$this->renderMessage('info','<b>C A U T I O N !</b><br>This is a standard entry. Changes could cause serious problems in the app.<br>Please only make changes if you are 100% sure.');
} ?>

<form method="post" action="<?php echo Routes::getUri('admin_routes_edit')."?id=".$route->getIdentifier(); ?>">
    <table class="table table-striped">
    	<tr>
    		<td>Identifier<br><small>unique key (can't be changed)</small></td>
    		<td><input class="form-control" type="text" name="idf" pattern="^([a-z]|[0-9]|_)+$" value="<?php echo $route->getIdentifier(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>URL in browser<br><small>from the app's directory</small></td>
    		<td><input class="form-control" type="text" name="url" value="<?php echo $route->getUrl(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Controller Class Name<br><small>without the addition "Controller" (e.g. AdminController = Admin)</small></td>
    		<td><input class="form-control" type="text" name="controller" value="<?php echo $route->getController(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Method within Class</td>
    		<td><input class="form-control" type="text" name="action" value="<?php echo $route->getAction(); ?>"required></td>
    	</tr>
    	<tr>
    		<td>Request Method(s)</td>
    		<td>
    			<select class="form-select" name="method" required>
	    			<option value="get" <?php if($route->getMethod() == 'get'){ echo 'selected'; } ?>>get</option>
	    			<option value="post" <?php if($route->getMethod() == 'post'){ echo 'selected'; } ?>>post</option>
	    			<option value="get|post" <?php if($route->getMethod() == 'get|post'){ echo 'selected'; } ?>>get und post</option>
	    		</select>
    		</td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_routes_index');?>">go back</a> 
		&bull; <input class="btn btn-primary" type="submit" name="editRoute" value="save">
		<input type="hidden" name="identifier" value="<?php echo $route->getIdentifier(); ?>">
	</p>
</form>