<h1><?php echo $deck->getName(); ?></h1>

<p class="text-muted">Hochgeladen von <i class="fas fa-user text-dark"></i> <?php echo $deck->getCreatorName(); ?> 
am <i class="fas fa-calendar-alt text-dark"></i> <?php echo $deck->getDate(); ?> 
in <i class="fas fa-folder text-dark"></i> <?php echo $deck->getCategoryName(); ?> - <?php echo $deck->getSubcategoryName(); ?></p>


<h4>Karten</h4>
<div class="text-center">
	<?php echo $deck->getDeckView(); ?>
</div>

<h4>Master</h4>
<div class="text-center">
	<?php echo $deck->getMasterCard(); ?>
</div>