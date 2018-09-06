<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=trade'; ?>">Trade Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=keep'; ?>"">Keep Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="<?php echo $member->getProfilLink().'&cat=collect'; ?>">Collect Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  href="<?php echo $member->getProfilLink().'&cat=master'; ?>">Master Karten</a>
  </li>
</ul>

<div class="my-4">
<?php foreach($collections as $deck_id => $collection){ ?>
	<div class="d-inline-block m-4 text-center">
		<h4><span class="deckname"><?php echo $deckdata[$deck_id]->getDeckname(); ?></span></h4>
    	<?php 
    	for($i=1; $i<=$decksize; $i++){  
    	    if(key_exists($i,$collection)) {
    	        echo $collection[$i]->getImageHtml();
    	    }else{
    	        echo $searchcard_html;
    	    }
    	    if($i%$cards_per_row == 0){ echo '<br>'; }
    	} 
    	?>
	</div>
<?php } ?>
</div>

<?php if(count($collections) == 0){ Layout::sysMessage('Keine Karten in dieser Kategorie'); } ?>