<h1>Master Karten</h1>

<p class="text-center">
	<a class="btn btn-outline-dark <?php if($curr_order == 'deckname'){ echo 'active'; } ?>" 
		href="<?php echo Routes::getUri('member_mastercards'); ?>?order_by=deckname">sortiert nach Deckname</a>
	<a class="btn btn-outline-dark <?php if($curr_order == 'date'){ echo 'active'; } ?>" 
		href="<?php echo Routes::getUri('member_mastercards'); ?>?order_by=date">sortiert nach Datum</a>
</p>

<?php echo $pagination; ?>

<p class="text-center">
<?php foreach($mastered_decks as $mastercard){ ?>

	<span class="d-inline-block text-center m-1">
		<span class="d-inline-block card-member-profil">
			<?php echo $mastercard->getDeck()->getMasterCard(); ?>
        	<?php if($mastercard->getPossessionCounter() > 1){ ?>
        		<span class="badge badge-dark"><?php echo $mastercard->getPossessionCounter(); ?></span>
        	<?php } ?>
		</span>
		<br>
    	<a class="deckname" href="<?php echo $mastercard->getDeck()->getDeckpageUrl(); ?>"><?php echo $mastercard->getDeck()->getDeckname(); ?></a>
    	<br>
    	<small><?php echo $mastercard->getDate(); ?></small>
    </span>

<?php } ?>
</p>

<?php if(count($mastered_decks) == 0){ Layout::sysMessage('Keine gemasterten Decks.'); } ?>


<?php echo $pagination; ?>