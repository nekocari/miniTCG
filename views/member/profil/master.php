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

<div class="my-4">
<?php foreach($cat_elements as $master_deck){
    echo $master_deck->getMasterCard();
}?>
</div>

<?php if(count($cat_elements) == 0){ Layout::sysMessage('Keine Karten in dieser Kategorie'); } ?>