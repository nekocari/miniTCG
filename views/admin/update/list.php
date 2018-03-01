<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard'); ?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Updates</li>
  </ol>
</nav>


<h1>Deck Updates</h1>

<p class="text-center">
	<a class="btn btn-primary" href="<?php echo Routes::getUri('deck_update_add'); ?>">neues Update anlegen</a>
</p>

<div class="table-responsive">
    <table class="table table table-sm">
    	<thead class="thead-light">
    	<tr>
    		<th>ID</th>
    		<th>Status</th>
    		<th>Datum</th>
    		<th></th>
    	</tr>
    	</thead>
    	<?php foreach($updates as $update){ ?>
    		<tr>
    			<td><?php echo $update->getId(); ?></td>
    			<td><?php echo $update->getStatus(); ?></td>
    			<td><?php echo $update->getDate(); ?></td>
    			<td class="text-right">
    				<?php if($update->getStatus() == 'new'){ ?>
    				<a class="btn btn-success btn-sm" href="<?php echo Routes::getUri('deck_update'); ?>?id=<?php echo $update->getId(); ?>&action=publish">
    				freischalten</a>
    				<a class="btn btn-danger btn-sm" href="<?php echo Routes::getUri('deck_update'); ?>?id=<?php echo $update->getId(); ?>&action=delete">
    				löschen</a>
    				<a class="btn btn-primary btn-sm" href="<?php echo Routes::getUri('deck_update_edit'); ?>?id=<?php echo $update->getId(); ?>">
    				<i class="fas fa-pencil-alt"></i> bearbeiten</a>
    				<?php } ?>
    			</td>
    		</tr>
    	<?php } ?>
    </table>
</div>