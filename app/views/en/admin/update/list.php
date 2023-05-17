<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard'); ?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Updates</li>
  </ol>
</nav>


<h1>Deck Updates</h1>

<p class="text-center">
	<a class="btn btn-primary" href="<?php echo Routes::getUri('deck_update_add'); ?>">create an Update</a>
</p>

<?php if($list->getCount() > 0){ ?>

	<div class="table-responsive">
	    <table class="table table table-sm">
	    	<thead class="table-light">
	    	<tr>
	    		<th>ID</th>
	    		<th>Status</th>
	    		<th>Decks</th>
	    		<th>Date</th>
	    		<th></th>
	    	</tr>
	    	</thead>
	    	<?php foreach($list->getItems() as $update){ ?>
	    		<tr>
	    			<td><?php echo $update->getId(); ?></td>
	    			<td><span class="badge <?php if(!$update->isPublic()){ echo 'badge-primary'; }else{ echo 'badge-secondary'; } ?>">
	    				<?php echo ucfirst($update->getStatus()); ?></span>
	    			</td>
	    			<td><small><?php foreach($update->getRelatedDecks() as $deck){ echo '&bull;&nbsp;'.$deck->getDeckname(); ?>
	    				<?php } ?></small>
	    			</td>
	    			<td><?php echo $update->getDate($this->login->getUser()->getTimezone()); ?></td>
	    			<td class="text-right text-nowrap">
	    				<?php if($update->getStatus() == 'new'){ ?>
	    				<a class="btn btn-success btn-sm" href="<?php echo Routes::getUri('deck_update'); ?>?id=<?php echo $update->getId(); ?>&action=publish">
	    				<i class="fas fa-unlock"></i> <span class="d-none d-xl-inline">publish</span></a>
	    				<a class="btn btn-danger btn-sm" href="<?php echo Routes::getUri('deck_update'); ?>?id=<?php echo $update->getId(); ?>&action=delete">
	    				<i class="fas fa-times"></i> <span class="d-none d-xl-inline">delete</span></a>
	    				<a class="btn btn-primary btn-sm" href="<?php echo Routes::getUri('deck_update_edit'); ?>?id=<?php echo $update->getId(); ?>">
	    				<i class="fas fa-pencil-alt"></i> <span class="d-none d-xl-inline">edit</span></a>
	    				<?php } ?>
	    			</td>
	    		</tr>
	    	<?php } ?>
	    </table>
	</div>
	
	<?php echo $list->getPagination()->getPaginationHTML(); ?>

<?php } ?>