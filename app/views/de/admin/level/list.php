<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Level</li>
  </ol>
</nav>

<h1>Level</h1>


<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Stufe</th>
    		<th>Name</th>
    		<th>Cards</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($level as $lv){ ?>
    	<tr style="white-space:nowrap">
    		<td><?php echo $lv->getId(); ?></td>
    		<td><?php echo $lv->getLevel(); ?></td>
    		<td><?php echo $lv->getName(); ?></td>
    		<td><?php echo $lv->getCards(); ?></td>
    		<td class="text-right">
    			<a href="<?php echo Routes::getUri('level_edit');?>?id=<?php echo $lv->getId(); ?>" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i> bearbeiten</a>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>
<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('level_add');?>">neues Level anlegen</a></p>