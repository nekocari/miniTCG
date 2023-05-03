<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Members</a></li>
    <li class="breadcrumb-item active" aria-current="page">manage Rights</li>
  </ol>
</nav>

<h1>Manage Rights</h1>

<h4>Member: <b><?php echo $member->getName(); ?></b></h4>


<table class="table">
<?php 
foreach($rights as $right){ 
	if(!in_array($right->getName(), $member_rights)){ 
?>
	<tr>
		<td>
			<span class="badge badge-secondary">inactiv</span>
		</td>
		<td>
			<?php echo $right->getName(); ?><br>
			<small class="text-muted"><?php echo $right->getDescription(); ?></small>
		</td>
		<td>
			<form method="POST" action="" class="form-inline">
				<input type="hidden" name="right_id" value="<?php echo $right->getId(); ?>">
				<button type="submit" name="add" value="add" class="btn btn-sm btn-primary">
					<i class="fas fa-plus"></i>
					add
				</button>
			</form>
		</td>
	</tr>
<?php 
	}else{ 
?>
	<tr>
		<td>
			<span class="badge badge-primary">activ</span>
		</td>
		<td>
			<?php echo $right->getName(); ?><br>
			<small class="text-muted"><?php echo $right->getDescription(); ?></small>
		</td>
		<td>
			<form method="POST" action="" class="form-inline">
				<input type="hidden" name="right_id" value="<?php echo $right->getId(); ?>">
				<button type="submit" name="remove" value="remove" class="btn btn-sm btn-danger">
					<i class="fas fa-times"></i> remove
				</button>
			</form>
		</td>
	</tr>
<?php 
	}
} 
?>
</table>