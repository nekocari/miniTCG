<h1>Karten Verwalten</h1>

<p class="text-center">
<?php foreach(Card::getAcceptedStatiObj() as $status){ ?>
	<a class="btn btn-outline-dark" href="<?php echo Routes::getUri('member_cardmanager')."?status=".$status->getId(); ?>"><?php echo strtoupper($status->getName()); ?></a>
<?php } ?>
</p>

<h2>Kategorie: <?php echo strtoupper($cardmanager->getStatus()->getName()); ?></h2>

<form name="sortCards" method="POST" action="">
	
	<?php echo $pagination; ?>
	
	<div>
	<?php foreach($cards as $card){ 
		
		//var_dump($card);
	?>
		<div class="d-inline-block text-center m-1 card-cardmanager ">
		
			<?php /*
			$title_text = '';
			if($card->onWishlist() AND !$card->deckInCollect() AND $card->missingInNotTradeable()){
				// Auf Wunschliste und Deck noch nicht in Collect oder einer nicht tauschbaren Kategorie
					$title_text = "Noch nicht in deiner Sammlung oder einer untauschbaren Kategorie";
					echo " card-missing-wishlist"; 
				}
				if($card->missingInCollect()){
					// wenn nicht in collect CSS Klasse einfügen
					echo " card-missing-collect"; }
				if($card->mastered()){ 
					echo " card-mastered"; } 
					foreach(CardStatus::getNotTradeable( ['position'=>'DESC']) as $nt_status){
					if($card->missingIn($nt_status->getId())){
						$title_text = "Noch nicht in ".$nt_status->getName();
						echo " card-missing-status-".$nt_status->getId();
						echo " card-missing-keep";
						break;
					}
				}
			*/  ?>
		
			<div title="<?php echo $title_text; ?>">
				<?php echo $card->getImageHtml(); ?>
			</div>
			<div>
				<small><?php echo $card->getName(); ?></small>
			</div>
			<div>
	        	<select class="form-select form-select-sm" name="newStatus[<?php echo $card->getId(); ?>]">
	        		<?php echo $card->getSortingOptionsHTML(); ?>
	        	</select>
	    	</div>
		</div>
	<?php } ?>
	
	</div>
	
	<?php echo $pagination; ?>
	
	<?php 
	if(count($cards) == 0){ 
		$this->renderMessage('info','In dieser Kategorie befinden sich derzeit keine Karten.'); 
	}else{ ?>
		<p class="text-center"><button class="btn btn-primary" role="submit" name="changeCardStatus" value="1">Karten einsortieren</button></p>
	<?php } ?>
	
	
	<input type="hidden" name="action" value="move_cards">
</form>

<?php include $this->partial('templates/card_color_legend.php'); ?>