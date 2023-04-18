<h1>Karten Verwalten</h1>

<p class="text-center">
<?php foreach(Card::getAcceptedStatiObj() as $status){ ?>
	<a class="btn btn-outline-secondary" href="<?php echo Routes::getUri('member_cardmanager')."?status=".$status->getId(); ?>"><?php echo strtoupper($status->getName()); ?></a>
<?php } ?>
</p>

<h2>Kategorie: <?php echo strtoupper($cardmanager->getStatus()->getName()); ?></h2>

<?php if($cardmanager->getCollectionDecks()){ foreach($cardmanager->getCollectionDecks() as $deck_id => $collection){ ?>

<form class="text-center row" name="sortCards" method="POST" action="">
    
    	<div class="col-lg col-sm-12 text-center mb-4">
    	
    		<h4><a href="<?php echo $collection->getDeckpageUrl(); ?>" class="deckname"><?php echo $collection->getDeckname(); ?></a></h4>
    		<div><?php echo $collection->getName(); ?></div>
    		
    		<div class="table-responsive">
    			<?php echo $cardmanager->collectionView($collection->getId()); ?>
            	<!-- display the card images or searchcard if card is not in collection TODO!! -->
    		
        	</div>
    	
        	<!-- action buttons - master or dissolve -->
    		<p class="m-1">
    		<?php if(count($cardmanager->getCollectionCards()[$collection->getId()]) == $collection->getSize()) { ?>
    			<button class="btn btn-success btn-small" name="master" value="<?php echo $deck_id; ?>">Mastercard abholen</button>
    		<?php }else{ ?>
    			<button class="btn btn-danger btn-small" name="dissolve" value="<?php echo $deck_id; ?>">Sammlung auflösen</button>
    		<?php } ?>
    		</p>	
    		
    	</div>

</form>
<?php }}else{ $this->renderMessage('info','In dieser Kategorie befinden sich derzeit keine Karten.'); } ?>