<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('level_index');?>">Level</a></li>
    <li class="breadcrumb-item active" aria-current="page">add</li>
  </ol>
</nav>

<h1>add Level</h1>

<form enctype="multipart/form-data" method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Level Rank:</td>
    		<td><input class="form-control" type="number" name="level"></td>
    	</tr>
    	<tr>
    		<td>Level Name:</td>
    		<td><input class="form-control" type="text" name="name"></td>
    	</tr>
    	<tr>
    		<td>Number of Cards needed:</td>
    		<td><input class="form-control" type="number" name="cards"></td>
    	</tr>
    	<tr>
    		<td>Level Badge:</td>
    		<td><input class="form-control" type="file" name="file"></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('level_index');?>">go back</a> 
		&bull; <input class="btn btn-primary" type="submit" name="addLevel" value="save">
	</p>
</form>