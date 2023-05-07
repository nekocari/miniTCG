<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('level_index');?>">Level</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Level</h1>

<form enctype="multipart/form-data" method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Level ID</td>
    		<td><input class="form-control" type="text" name="id" value="<?php echo $level->getId(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Level Rank:</td>
    		<td><input class="form-control" type="number" name="level" value="<?php echo $level->getLevel(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Level Name:</td>
    		<td><input class="form-control" type="text" name="name" value="<?php echo $level->getName(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Number of Cards needed:</td>
    		<td><input class="form-control" type="number" name="cards" value="<?php echo $level->getCards(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Replace Level Badge?</td>
    		<td><?php echo $level->getLevelBadgeHTML(); ?><br>
    			<input class="form-control" type="file" name="file"></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('level_index');?>">go back</a> 
		&bull; <input class="btn btn-primary" type="submit" name="editLevel" value="save">
		<input type="hidden" name="id" value="<?php echo $level->getId(); ?>">
	</p>
</form>