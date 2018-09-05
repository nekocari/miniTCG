<h1>Master Karten</h1>

<p class="text-center">
	<a class="btn btn-outline-secondary" href="<?php echo Routes::getUri('member_mastercards'); ?>?order_by=deckname">sortiert nach Deckname</a>
	<a class="btn btn-outline-secondary" href="<?php echo Routes::getUri('member_mastercards'); ?>?order_by=master_date">sortiert nach Datum</a>
</p>

<?php foreach($mastered_decks as $deck){ ?>
<div class="d-inline-block m-2 text-center">
	<?php echo $deck->getDeck()->getMasterCard(); ?><br>
	<a class="deckname" href="<?php echo $deck->getDeck()->getDeckpageUrl();?>"><?php echo $deck->getDeck()->getDeckname(); ?></a><br>
	<?php echo $deck->getDate(); ?>
</div>
<?php } ?>

<?php if(count($mastered_decks) == 0){ Layout::sysMessage('Keine gemasterten Decks.'); } ?>

<?php echo $pagination; ?>