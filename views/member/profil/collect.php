<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=trade'; ?>">Trade Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $member->getProfilLink().'&cat=keep'; ?>">Keep Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="<?php echo $member->getProfilLink().'&cat=collect'; ?>">Collect Karten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  href="<?php echo $member->getProfilLink().'&cat=master'; ?>">Master Karten</a>
  </li>
</ul>


<div class="my-4 row">
<?php foreach($collections as $deck_id => $collection){ ?>
	
	<div class="col-lg col-sm-12 text-center mb-4">
	
    	<h4><a href="<?php echo $deckdata[$deck_id]->getDeckpageUrl(); ?>" class="deckname"><?php echo $deckdata[$deck_id]->getDeckname(); ?></a></h4>
    	<div><?php echo $deckdata[$deck_id]->getName(); ?></div>
    	
    	<div class="table-responsive">
    		<div style="white-space:nowrap;" class="<?php if($deckdata[$deck_id]->isPuzzle()){ echo "puzzle-view"; } ?>">
        	<?php for($i=1; $i<=$decksize; $i++){  
        	    if(key_exists($i,$collection)) {
        	        echo $collection[$i]->getImageHtml();
        	    }else{
        	        if(!$deckdata[$deck_id]->isPuzzle()){
        	            echo $searchcard_html;
        	        }else{
        	            echo ${'searchcard_html_'.$i};
        	        }
        	    }
        	    if($i%$cards_per_row == 0){ echo '<br>'; }
        	} ?>
        	</div>
    	</div>
	</div>
<?php } ?>
</div>

<?php if(count($collections) == 0){ Layout::sysMessage('Keine Karten in dieser Kategorie'); } ?>