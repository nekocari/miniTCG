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
<?php foreach($cat_elements as $card){ ?>
    <div class="d-inline-block card-member-profil">
        <?php echo $card->getImageHtml(); ?>
    	<?php if($card->getPossessionCounter() > 1){ ?>
    		<span class="badge badge-dark"><?php echo $card->getPossessionCounter(); ?></span>
    	<?php } ?>
	</div>
<?php } ?>
</div>

<?php if(count($cat_elements) == 0){ Layout::sysMessage('Keine Karten in dieser Kategorie'); } ?>