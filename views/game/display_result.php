<h1><?php echo $game->getName(); ?></h1>

<h2>Ergebnis</h2>

<p class="text-center"><?php echo $reward['text']; ?></p>

<p class="text-center">
<?php 

foreach($reward['cards'] as $card){
    echo $card->getImageHTML();
} ?>
</p>