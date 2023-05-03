<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Members</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Member</h1>

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
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_member_index');?>">go back</a> 
	&bull; <input class="btn btn-primary" type="submit" name="updateMemberdata" value="save">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
</p>
</form>

<hr>

<form method="post" action="" onsubmit="return confirm('This action can not be undone.\n Are you sure?');">
	<p class="text-center">
    	<input class="btn btn-danger" type="submit" name="deleteMemberdata" value="DELETE member">
    	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    </p>
</form>