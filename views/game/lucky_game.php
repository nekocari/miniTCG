<h1><?php echo $game->getName(); ?></h1>
<h2>lass uns spielen!</h2>

<form method="post">
<p class="text-center"><?php echo $game->getDescription(); ?></p>

<p class="text-center">
<?php foreach($game->getChoices() as $value => $choice){ ?>
	<button class="btn btn-outline-secondary" name="choice[]" type="submit"><?php echo $choice; ?></button>
	<input type="hidden" name="play" value="1">
<?php } ?>
</p>
</form>