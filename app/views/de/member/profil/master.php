<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=trade'; ?>">Trade Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=keep'; ?>">Keep Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=collect'; ?>">Collect Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active"  href="<?php echo $member->getProfilLink().'&cat=master'; ?>">Master Karten</a>
  </li>
</ul>

<div class="my-4 text-center">
<?php foreach($cat_elements as $mastercard){ ?>
	<span class="d-inline-block text-center m-1">
		<span class="d-inline-block card-member-profil">
			<?php echo $mastercard->getDeck()->getMasterCard(); ?>
        	<?php if($mastercard->getPossessionCounter() > 1){ ?>
        		<span class="badge badge-dark"><?php echo $mastercard->getPossessionCounter(); ?></span>
        	<?php } ?>
		</span>
		<br>
    	<a class="deckname" href="<?php echo $mastercard->getDeck()->getDeckpageUrl(); ?>"><?php echo $mastercard->getDeck()->getDeckname(); ?></a>
    	<br>
    	<small><?php echo $mastercard->getDate(); ?></small>
    </span>
<?php } ?>
</div>

<?php if(count($cat_elements) == 0){ $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>