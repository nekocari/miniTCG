<h1>Karten Verwalten</h1>

<p class="text-center">
<?php foreach(Card::getAcceptedStatiObj() as $status){ ?>
	<a class="btn btn-outline-dark" href="<?php echo Routes::getUri('member_cardmanager')."?status=".$status->getId(); ?>"><?php echo strtoupper($status->getName()); ?></a>
<?php } ?>
</p>

<h2>Kategorie: <?php echo strtoupper($cardmanager->getStatus()->getName()); ?></h2>

<?php echo $pagination; ?>

<?php if($collections){ foreach($collections as $deck_id => $collection){ ?>

<form class="text-center row" name="sortCards" method="POST" action="">
    
	<div class="col-lg col-sm-12 text-center mb-4">
		<h4><a href="<?php echo $collection->getDeckpageUrl(); ?>" class="deckname"><?php echo $collection->getDeckname(); ?></a></h4>
		<div><?php echo $collection->getName(); ?></div>
		
		<!-- display the card images or filler if card is not in collection -->
		<div class="table-responsive">
			<?php echo $cardmanager->collectionView($collection->getId(),true); ?>
		</div>
		
		<!-- action buttons - master or dissolve -->
		<p class="my-2">
 			<?php if(count($cardmanager->getCollectionCards()[$collection->getId()]) == $collection->getSize()) { ?>
				<button class="btn btn-success btn-small" name="action" value="master">Mastercard abholen</button>
				&bull;
			<?php } ?>
			<button class="btn btn-danger btn-small" name="action" value="dissolve">Sammlung auflösen</button>
		</p>
	</div>
	<input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
</form>
<?php }}else{ $this->renderMessage('info','In dieser Kategorie befinden sich derzeit keine Karten.'); } ?>

<?php echo $pagination; ?>