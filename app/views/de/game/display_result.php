<h1><?php echo $game_name; ?></h1>

<h2>Ergebnis</h2>

<p class="text-center"><?php echo $reward['text']; ?></p>

<div class="text-center">
	<?php if(isset($reward['cards']) AND !is_null($reward['cards'])){ foreach($reward['cards'] as $card){ echo $card->getImageHTML(); }}else{ echo '- keine Karten gefunden -'; } ?>
</div>
<div class="text-center h3">
	<?php if(isset($reward['money'])){ echo $reward['money']; } ?>
</div>

<p class="text-center">
	<a class="btn btn-dark" href="javascript:history.go(-2)">zurück zur Übersicht</a>
</p>