<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="<?php echo $member->getProfilLink().'&cat=trade'; ?>">Trade Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=keep'; ?>">Keep Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=collect'; ?>">Collect Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  href="<?php echo $member->getProfilLink().'&cat=master'; ?>">Master Karten</a>
  </li>
</ul>

<div class="my-4">
    <div class="alert alert-info" role="alert">
    	Klicke auf eine Karte um <?php echo $member->getName(); ?> ein Tauschangebot zu machen!
    </div>
    <?php foreach($cat_elements as $card){ ?>
        <div class="d-inline-block card-member-profil <?php if($card->missingInKeep()){ echo " card-missing-keep"; } 
        if($card->missingInCollect()){ echo " card-missing-collect"; } if($card->mastered()){ echo " card-mastered"; } ?>">
        	<a href="<?php echo RoutesDb::getUri('trade').'?card='.$card->getId(); ?>">
            	<?php echo $card->getImageHtml(); ?>
        	</a>
        	<?php if($card->getPossessionCounter() > 1){ ?>
    		<span class="badge badge-dark"><?php echo $card->getPossessionCounter(); ?></span>
    		<?php } ?>
    </div>
	<?php } ?>
</div>


<div class="text-center my-2">
	<small>
		<span class="d-inline-block card-member-profil card-missing-keep">fehlend in Keep</span>
		<span class="d-inline-block card-member-profil card-missing-collect">fehlend in Collect</span>
		<span class="d-inline-block card-member-profil card-mastered">Deck gemastert</span>
	</small>
</div>
<?php if(count($cat_elements) == 0){ $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>