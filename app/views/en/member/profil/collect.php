<?php 
/*
 * Classes used:
 */
?>

<?php if(count($collections)){ ?>
	<div class="my-4 row">
		<?php foreach($collections as $deck_id => $collection){ ?>
			<div class="col-lg col-sm-12 text-center mb-4">
			
		    	<h4><a href="<?php echo $collection->getDeckpageUrl(); ?>" class="deckname"><?php echo $collection->getDeckname(); ?></a></h4>
		    	<div><?php echo $collection->getName(); ?></div>
		    	
		    	<div class="table-responsive">
		    		<div style="white-space:nowrap;">		        	
					<?php echo $cardmanager->collectionView($deck_id); ?>
		        	</div>
		    	</div>
			</div>	
		<?php } ?>
	</div>
<?php }else{ ?>
	<div class="mb-2 mt-4">
		<?php $this->renderMessage('info','no cards in this category'); ?>
	</div>
<?php } ?>