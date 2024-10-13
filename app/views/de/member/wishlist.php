<h1>Wunschliste</h1>

<?php if($wishlist->getCount() > 0){ ?>

<?php echo $wishlist->getPagination()->getPaginationHTML(); ?>

<div class="table-responsive">
	<table class="table">
		<thead>
		<tr>
			<th>Deck</th>
			<th class="d-none d-md-table-cell text-center">hinzugefügt am</th>
			<th class="text-right">Aktion</th>
		</tr>
		</thead>
		<?php foreach($wishlist->getItems() as $entry){ ?>
	    	<tr>
	    		<td>
					<a class="deckname" href="<?php echo $entry->getDeck()->getDeckpageUrl(); ?>"><?php echo $entry->getDeck()->getDeckname(); ?></a><br>
					<span><?php echo $entry->getDeck()->getName(); ?></span>
				</td>
	    		<td class="d-none d-md-table-cell text-center"><?php echo $entry->getDate(); ?></td>
	    		<td class="text-right">
	    			<form method="POST" action="">
	    				<button class="btn btn-sm btn-danger" name="delete" value="<?php echo $entry->getId(); ?>">
		    				<i class="fas fa-times"></i> 
							<span class="d-none d-md-inline">entfernen</span>
	    				</button>
	    			</form>
	    		</td>
	    	</tr>
		<?php } ?>
	</table>
</div>

<?php echo $wishlist->getPagination()->getPaginationHTML(); ?>

<?php 
	}else{ 
		$this->renderMessage('info','Keine Decks auf der Wunschliste');
	} 
?>