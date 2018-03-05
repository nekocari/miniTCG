<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="<?php echo $member->getProfilLink().'&cat=trade'; ?>">Trade Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=keep'; ?>"">Keep Karten</a>
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
    <a href="<?php echo Routes::getUri('member_trade'); ?>"><?php echo $card->getImageHtml(); ?></a>
<?php } ?>
</div>