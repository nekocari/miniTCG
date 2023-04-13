<h1><span class="deckname"><?php echo $deck->getDeckname(); ?></span> <?php echo $deck->getName(); ?></h1>

<div class="d-flex flex-wrap">
	<div class="my-2 col-12 col-md-4 text-center">
		<?php echo $deck->getMasterCard(); ?>
	</div>
	
	<div class="my-2 col-12 col-md-8">
		<p>
			hochgeladen von <span class="badge badge-dark ml-1"><i class="fas fa-user"></i></span>
			<a href="<?php echo $deck->getCreatorObj()->getProfilLink(); ?>"><?php echo $deck->getCreatorName(); ?></a> 
    		am <span class="badge badge-dark ml-1"><i class="fas fa-calendar-alt"></i></span> <?php echo $deck->getDate(); ?> <br>
    		in <span class="badge badge-dark ml-1"><i class="fas fa-folder"></i></span>
    		<a href="<?php echo $deck->getCategory()->getLinkUrl(); ?>"><?php echo $deck->getCategoryName(); ?> - <?php echo $deck->getSubcategoryName(); ?></a>
		</p>
		
		<p><?php echo $deck->getDescription('html'); ?></p>
    	
	</div>
</div>

<hr>

<div class="my-4 text-center table-responsive <?php if($deck->isPuzzle()){ echo "puzzle-view"; } ?>" style="white-space:nowrap;">
	<?php echo $deck->getDeckView(); ?>
</div>

<hr>

<div class="d-flex flex-wrap">
	<div class="col-12 col-md-6 text-left">
    	<h3 class="m-0"><i class="fas fa-folder-plus"></i> Sammler</h3>
    	<div style="overflow:auto; max-height: 25vh;">
    	<?php if(count($deck->getCollectorMembers())){ foreach($deck->getCollectorMembers() as $member){ ?>
    		<a class="btn btn-sm btn-outline-primary" href="<?php echo $member->getProfilLink(); ?>">
    			<i class="fas fa-user"></i> <?php echo $member->getName(); ?>
    		</a> 
    	<?php }}else{ ?>
    		- niemand -
    	<?php } ?>
    	</div>
	</div>
	
	
	<div class="col-12 col-md-6 text-right">
		<h3 class="m-0">Master <i class="fas fa-trophy"></i></h3>
    	<div style="overflow:auto; max-height: 25vh;">
    	<?php if(count($deck->getMasterMembers())){ foreach($deck->getMasterMembers() as $member){ ?>
    		<a class="btn btn-sm btn-outline-primary" href="<?php echo $member->getProfilLink(); ?>">
    			<i class="fas fa-user"></i> <?php echo $member->getName(); ?>
    		</a> 
    	<?php }}else{ ?>
    		- niemand -
    	<?php } ?>
    	</div>
	</div>
</div>

