<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('news_index');?>">News</a></li>
    <li class="breadcrumb-item active" aria-current="page">Update</li>
  </ol>
</nav>

<h1>Link Update</h1>

<form method="POST" action="">

<?php if($linked_update){ ?>
	<h3>linked Update</h3>
    <div class="card my-3">
    	<div class="card-header">
    		<span class="badge bg-<?php if($linked_update->getStatus() == 'new'){ echo "primary"; }else{ echo "secondary"; } ?>">
    			<?php echo $linked_update->getStatus(); ?>
    		</span>
    		<?php echo $linked_update->getDate($this->login->getUser()->getTimezone()); ?>
    	</div>
    	<div class="table-responsive">
    		<div class="card-body p-2" style="white-space: nowrap">
            	<?php foreach($linked_update->getRelatedDecks() as $deck){ echo $deck->getMasterCard(); } ?>
        	</div>
    	</div>
		<div class="card-footer text-right p-1">
			<button class="btn btn-sm btn-danger" type="submit" name="remove" value="<?php echo $linked_update->getId(); ?>">
				remove link
			</button>
		</div>
	</div>
<?php } ?>


<?php if(count($unlinked_updates)){ ?>
    <h3>unlinked Updates</h3>
    <?php foreach($unlinked_updates as $unlinked_update){ ?>
    <div class="card my-3">
    	<div class="card-header">
    		<span class="badge bg-<?php if($unlinked_update->getStatus() == 'new'){ echo "primary"; }else{ echo "secondary"; } ?>">
    			<?php echo $unlinked_update->getStatus(); ?>
    		</span>
    		<?php echo $unlinked_update->getDate($this->login->getUser()->getTimezone()); ?>
    	</div>
    	<div class="table-responsive">
    		<div class="card-body p-2" style="white-space: nowrap">
            	<?php foreach($unlinked_update->getRelatedDecks() as $deck){ echo $deck->getMasterCard(); } ?>
        	</div>
    	</div>
		<div class="card-footer text-right p-1">
			<button class="btn btn-sm btn-primary" type="submit" name="add" value="<?php echo $unlinked_update->getId(); ?>">
				 <i class="fas fa-plus"></i> link
			</button>
		</div>
	</div>
<?php } } ?>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('news_index');?>">go back</a>
</p>



</form>