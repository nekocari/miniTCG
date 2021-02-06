<h1>Karten Verwalten</h1>

<p class="text-center">
<?php foreach($accepted_status as $status){ ?>
	<a class="btn btn-outline-secondary" href="<?php echo Routes::getUri('member_cardmanager')."?status=$status"; ?>"><?php echo strtoupper($status); ?></a>
<?php } ?>
</p>

<h2>Kategorie: <?php echo strtoupper($curr_status); ?></h2>

<form class="text-center" name="sortCards" method="POST" action="">
<?php foreach($collections as $deck_id => $collection){ ?>
	<div class="d-inline-block m-4 text-center <?php if($deckdata[$deck_id]->isPuzzle()){ echo " puzzle-view"; } ?>">
		<h4><?php echo $deckdata[$deck_id]->getName(); ?></h4>
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
		<p>
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