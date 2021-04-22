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
<?php foreach($cat_elements as $master_deck){ ?>
	<span class="d-inline-block text-center m-1">
    	<?php echo $master_deck->getMasterCard(); ?><br>
    	<a class="deckname" href="<?php echo $master_deck->getDeckpageUrl(); ?>"><?php echo $master_deck->getDeckname(); ?></a><br>
    	<?php echo $master_deck->getDate(); ?>
    </span>
<?php } ?>
</div>

<?php if(count($cat_elements) == 0){ Layout::sysMessage('Keine Karten in dieser Kategorie'); } ?>