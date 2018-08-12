<h1><?php echo $member->getName(); ?></h1>
<div class="mb-2">
	<span class="badge badge-dark">Level <?php echo $member->getLevel(); ?></span> |
	<span class="badge badge-secondary"><?php echo $member->getCardCount(); ?> Karten</span> |
	<span class="badge badge-primary"><?php echo $member->getMasterCount(); ?> Master</span>
</div>

<?php if($member->getInfoText()) { ?>  	
    <p class="text-right">
    <button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    	Infos zu <span class="font-weight-bold"><?php echo $member->getName(); ?></span>
    </button>
    </p>
    
    
    <div class=" my-2 collapse" id="collapseExample">
      <div class="card card-body p-1">
    	<?php echo $member->getInfoText(); ?>
      </div>
    </div>
<?php } ?>
        
<?php include $partial_uri ?>