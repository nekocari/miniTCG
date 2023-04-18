<h1><?php echo $member->getName(); ?></h1>
<div class="mb-2">
	<span class="badge badge-dark">Level <?php echo $member->getLevel('object')->getName(); ?></span> |
	<span class="badge badge-secondary"><?php echo $member->getCardCount(); ?> Karten</span> |
	<span class="badge badge-primary"><?php echo $member->getMasterCount(); ?> Master</span>
</div>

<?php if($member->getInfoText()) { ?>  	
    <p class="text-left">
    <button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
    	Infos und Tauschregeln von <span class="font-weight-bold"><?php echo $member->getName(); ?></span>
    </button>
    </p>
    <div class=" my-2 collapse" id="collapse">
      <div class="card card-body p-1">
    	<?php echo $member->getInfoText(); ?>
      </div>
    </div>
<?php } ?>

<ul class="nav nav-tabs">
	<?php foreach(Card::getAcceptedStatiObj() as $status){ if($status->isPublic()){ ?>
  <li class="nav-item">
    <a class="nav-link <?php if($cat == $status){ echo 'active'; } ?>" href="<?php echo $member->getProfilLink().'&cat='.$status->getId(); ?>"><?php echo $status->getName(); ?> Karten</a>
  </li>
  	<?php }} ?>
  <li class="nav-item">
    <a class="nav-link <?php if($cat == 'master'){ echo 'active'; } ?>" href="<?php echo $member->getProfilLink().'&cat=master'; ?>">Master Karten</a>
  </li>
</ul>
        
<?php include $this->partial($partial_uri); ?>

<?php echo $pagination; ?>