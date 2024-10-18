<h1><?php echo $member->getName(); ?></h1>

<div class="d-md-flex m-2">
	<div><img src="<?php echo $member->getMemberCardUrl(); ?>"></div>
	<div class="w-100 mb-2 px-md-3">
		angemeldet am: <?php echo $member->getJoinDate($this->login->getUser()->getTimezone()); ?><br>
		letzter Login: <?php echo $member->getLoginDate($this->login->getUser()->getTimezone()); ?>
		<br>
		<span class="badge bg-dark">Stufe: <?php echo $member->getLevel('object')->getName(); ?></span> |
		<span class="badge bg-secondary"><?php echo $member->getCardCount(); ?> Karten</span> |
		<span class="badge bg-primary"><?php echo $member->getMasterCount(); ?> Master</span>
	</div>
	<div><?php echo $member->getLevel('object')->getLevelBadgeHTML(); ?></div>
</div>


<div class="text-right mb-4"><?php echo $member->getMessageLink('Nachricht senden','btn btn-primary'); ?></div>
	
	
<?php if($member->getInfoText()) { ?>  	
    <p class="text-left">
    <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse" aria-expanded="false" aria-controls="collapse">
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
<div class="text-end"><a href="<?php echo $_SERVER['REQUEST_URI'].'&filter=needed'; ?>" class="btn btn-sm btn-outline-secondary">nur benötigte Karten</a></div>
        
<?php include $this->partial($partial_uri); ?>

<?php echo $pagination; ?>