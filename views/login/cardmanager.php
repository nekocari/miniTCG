<h1>Karten Verwalten</h1>

<p class="text-center">
<?php foreach($accepted_status as $status){ ?>
	<a class="btn btn-outline-secondary" href="<?php echo Routes::getUri('member_cardmanager')."?status=$status"; ?>"><?php echo strtoupper($status); ?></a>
<?php } ?>
</p>

<h2>Kategorie: <?php echo strtoupper($curr_status); ?></h2>

<form name="sortCards" method="POST" action="">

<?php echo $pagination; ?>

<div>
<?php foreach($cards as $card){ ?>
	<div class="d-inline-block text-center m-1 card-cardmanager
	   <?php if($card->missingInKeep()){ echo " card-missing-keep"; } if($card->missingInCollect()){ echo " card-missing-collect"; } ?>">
		<div>
			<?php echo $card->getImageHtml(); ?>
		</div>
		<div>
			<?php echo $card->getName(); ?>
		</div>
		<div>
        	<select class="form-control form-control-sm" name="newStatus[<?php echo $card->getId(); ?>]">
        		<?php echo $card->getSortingOptionsHTML(); ?>
        	</select>
    	</div>
	</div>
<?php } ?>
</div>

<?php echo $pagination; ?>

<?php if(count($cards) == 0){ Layout::sysMessage('In dieser Kategorie befinden sich derzeit keine Karten.'); }else{ ?>
<p class="text-center"><button class="btn btn-primary" role="submit" name="changeCardStatus" value="1">Karten einsortieren</button></p>
<?php } ?>
</form>