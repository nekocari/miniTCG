<h1><?php echo $deck->getName(); ?></h1>

<div class="row">
	<div class="my-4 col-12 col-md-4 text-center"><?php echo $deck->getMasterCard(); ?></div>
	<div class="my-4 col-12 col-md-8">
		<p>
			hochgeladen von <i class="fas fa-user text-dark"></i> <?php echo $deck->getCreatorName(); ?> 
    		am <i class="fas fa-calendar-alt text-dark"></i> <?php echo $deck->getDate(); ?> <br>
    		in <i class="fas fa-folder text-dark"></i> <?php echo $deck->getCategoryName(); ?> - <?php echo $deck->getSubcategoryName(); ?>
		</p>
		<p>Sammler: 
		<?php foreach($deck->getCollectorMembers() as $member){ ?>
    		<a class="btn btn-sm btn-outline-primary" href="<?php echo $member->getProfilLink(); ?>">
    			<i class="fas fa-user"></i> <?php echo $member->getName(); ?>
    		</a> 
    	<?php } ?>
    	</p>
		<p><i class="fas fa-trophy"></i> Master: 
		<?php foreach($deck->getMasterMembers() as $member){ ?>
    		<a class="btn btn-sm btn-outline-primary" href="<?php echo $member->getProfilLink(); ?>">
    			<i class="fas fa-user"></i> <?php echo $member->getName(); ?>
    		</a> 
    	<?php } ?>
    	</p>
	</div>
</div>

<hr>

<div class="my-4 text-center <?php if($deck->isPuzzle()){ echo "puzzle-view"; } ?>">
	<?php echo $deck->getDeckView(); ?>
</div>