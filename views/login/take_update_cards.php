<h1>Update Karten nehmen</h1>

<p>Pro Deck des aktuellen Updates bekommst du eine Zufallskarte!</p>

<?php if($show_take_button){ ?>
    <form name="sortCards" method="POST" action="">
    <p class="text-center"><button class="btn btn-primary" role="submit" name="takeUpdateCards" value="1">Karten nehmen</button></p>
    </form>
<?php } ?>

<div class="text-center">
<?php foreach($update_decks as $deck){ ?>
	<div class="d-inline-block text-center m-2">
		<div>
			<?php echo $deck->getDeckView(); ?>
		</div>
		<div class="py-2">
			<?php echo $deck->getMasterCard(); ?>
		</div>
	</div>
<?php } ?>
</div>