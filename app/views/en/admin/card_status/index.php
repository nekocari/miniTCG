<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Manager Categories</li>
  </ol>
</nav>

<h1>Manager Categories</h1>
<p>Customize the categories for sorting the Cards.<p>

<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('admin_card_status_add');?>">add Category</a></p>

<form  method="POST" action="">
<div style="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th style="height:90px">ID</th>
    		<th >Name</th>
    		<th class=""><span class="">Positioning</span></th>
    		<th class="text-center"><span class="text-deg80" >tradeable?</span></th>
    		<th class="text-center"><span class="text-deg80" >new?</span></th>
    		<th class="text-center"><span class="text-deg80" >collecton?</span></th>
    		<th class="text-center"><span class="text-deg80" >visible?</span></th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($categories as $entry){ ?>
    	<tr style="white-space:nowrap">
    		<td><?php echo $entry->getId(); ?></td>
    		<td><input class="form-control" style="min-width:80px;" type="text" name="name[<?php echo $entry->getId()?>]" value="<?php echo $entry->getName(); ?>" required></td>
    		<td><input class="form-control" style="max-width:80px;" type="number" size="2" name="position[<?php echo $entry->getId()?>]" value="<?php echo $entry->getPosition(); ?>" required></td>
    		<td class="text-center">
    			<input type="checkbox" name="tradeable[<?php echo $entry->getId()?>]" value="1" <?php if($entry->isTradeable()){ echo 'checked'; } ?>>
    		</td>
    		<td class="text-center"><input type="radio" name="new" value="<?php echo $entry->getId()?>" <?php if($entry->isNew()){ echo 'checked'; } ?>></td>
    		<td class="text-center"><input type="radio" name="collections" value="<?php echo $entry->getId()?>" <?php if($entry->isCollections()){ echo 'checked'; } ?>></td>
    		<td class="text-center"><input type="checkbox" name="public[<?php echo $entry->getId()?>]" value="1" <?php if($entry->isPublic()){ echo 'checked'; } ?>></td>
    		<td class="text-right">
    			<a class="btn btn-danger btn-sm del-link mx-1" href="<?php echo Routes::getUri('admin_card_status_delete').'?id='.$entry->getId(); ?>"
    			onclick="return confirm('Are you sure you want to delete <?php echo $entry->getName(); ?>?');">
    				<i class="fas fa-times"></i> <span class="d-none d-md-inline">delete</span>
    			</a>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>
<p class="text-center">
	<input class="btn btn-primary" type="submit" name="updateCardStatus" value="save">
</p>
</form>