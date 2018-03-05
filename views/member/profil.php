<h1><?php echo $member->getName(); ?></h1>

<div class="row my-4">
	<div class="col-12 col-md-8">
    	Level: <?php echo $member->getLevel(); ?><br>
        Karten: <?php echo $member->getCardCount(); ?><br>
        Master: <?php echo $member->getMasterCount(); ?>
	</div>
</div>

<?php include $partial_uri ?>