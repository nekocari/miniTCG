<h1>Karten Verwalten</h1>

<p class="text-center">
<?php foreach($accepted_status as $status){ ?>
	<a class="btn btn-outline-secondary" href="<?php echo Routes::getUri('member_cardmanager')."?status=$status"; ?>"><?php echo strtoupper($status); ?></a>
<?php } ?>
</p>

<h2>Kategorie: Collect</h2>

<form class="text-center row" name="sortCards" method="POST" action="">
<?php foreach($collections as $deck_id => $collection){ ?>
    
    	<div class="col-lg col-sm-12 text-center mb-4">
    	
    		<h4><a href="<?php echo $deckdata[$deck_id]->getDeckpageUrl(); ?>" class="deckname"><?php echo $deckdata[$deck_id]->getDeckname(); ?></a></h4>
    		<div><?php echo $deckdata[$deck_id]->getName(); ?></div>
    		
    		<div class="table-responsive">
            	<div style="white-space: nowrap;" class="<?php if($deckdata[$deck_id]->isPuzzle()){ echo "puzzle-view"; } ?>">
            	<!-- display the card images or searchcard if card is not in collection -->
            	<?php 
            	for($i=1; $i<=$decksize; $i++){  
            	    if(key_exists($i,$collection)) {
            	        echo $collection[$i]->getImageHtml();
            	    }else{
            	        echo '<a href="'.Routes::getUri('card_search').'?deck='.$deck_id.'&number='.$i.'" class="card-link">';
            	        if(!$deckdata[$deck_id]->isPuzzle()){
            	           echo $searchcard_html;
            	        }else{
            	           echo ${'searchcard_html_'.$i};
            	        }
            	        echo '</a>';
            	    }
            	    if($i%$cards_per_row == 0){ echo '<br>'; }
            	} 
            	?>
            	</div>
    		
        	</div>
    	
        	<!-- action buttons - master or dissolve -->
    		<p class="m-1">
    		<?php if(count($collection) == $decksize) { ?>
    			<button class="btn btn-success btn-small" name="master" value="<?php echo $deck_id; ?>">Mastercard abholen</button>
    		<?php }else{ ?>
    			<button class="btn btn-danger btn-small" name="dissolve" value="<?php echo $deck_id; ?>">Sammlung auflösen</button>
    		<?php } ?>
    		</p>	
    		
    	</div>
<?php } ?>

</form>

<?php if(count($cards) == 0){ Layout::sysMessage('In dieser Kategorie befinden sich derzeit keine Karten.'); } ?>