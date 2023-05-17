<h1><span class="deckname"><?php echo $deck->getDeckname(); ?></span> <?php echo $deck->getName(); ?></h1>

<?php if(!$deck->isPublic()){ $this->renderMessage('info','Dieses Deck kann noch nicht gesammelt werden.'); ?>
	<div class="row">
		<div class="col"><span class="btn btn-outline-primary disabled"><?php echo $deck->getVoteCount(); ?> <i class="fas fa-thumbs-up"></i> Stimmen</span></div>
		<?php if(!$deck->hasVoted($this->login)){ ?>
			<form method="POST" action="" class="col text-right">
				<button class="btn btn-primary" role="submit" name="vote"><i class="fas fa-thumbs-up"></i> abstimmen</button>
			</form>
		<?php }else{ ?>
			<div class="col text-right">Du hast bereits abgestimmt!</div>
		<?php } ?>
	</div>
	<hr>
<?php } ?>

<div class="d-flex flex-wrap">
	<div class="my-2 col-12 col-md-4 text-center">
		<?php echo $deck->getMasterCard(); ?>
	</div>
	
	<div class="my-2 col-12 col-md-8">
		<p>
			hochgeladen von <span class="badge bg-dark ms-1"><i class="fas fa-user"></i></span>
			<a href="<?php echo $deck->getCreatorObj()->getProfilLink(); ?>"><?php echo $deck->getCreatorName(); ?></a> 
    		am <span class="badge bg-dark ms-1"><i class="fas fa-calendar-alt"></i></span> 
    		<?php if($this->login->isloggedIn()){ echo $deck->getDate($this->login->getUser()->getTimezone()); } else { echo $deck->getDate(); } ?> <br>
    		in <span class="badge bg-dark ms-1"><i class="fas fa-folder"></i></span>
    		<a href="<?php echo $deck->getCategory()->getLinkUrl(); ?>"><?php echo $deck->getCategoryName(); ?> - <?php echo $deck->getSubcategoryName(); ?></a>
		</p>
		
		<p><?php echo $deck->getDescription('html'); ?></p>
		
		<?php if($deck->onWishlist($this->login)){ ?>
			<form method="post" class="text-right"><button class="btn btn-outline-danger" name="wishlist" value="remove"><i class="far fa-heart"></i> <del>Wunschliste</del></button></form>
		<?php }else{ ?>
			<form method="post" class="text-right"><button class="btn btn-primary" name="wishlist" value="add"><i class="fas fa-heart"></i> Wunschliste</button></form>
		<?php } ?>
    	
	</div>
</div>

<hr>

<div class="my-4 text-center table-responsive" style="white-space:nowrap;">
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

