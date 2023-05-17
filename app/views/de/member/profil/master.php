<div class="my-4 text-center">
<?php foreach($cat_elements as $mastercard){ ?>
	<span class="d-inline-block text-center m-1">
		<span class="d-inline-block card-member-profil">
			<?php echo $mastercard->getDeck()->getMasterCard(); ?>
        	<?php if($mastercard->getPossessionCounter() > 1){ ?>
        		<span class="badge bg-dark"><?php echo $mastercard->getPossessionCounter(); ?></span>
        	<?php } ?>
		</span>
		<br>
    	<a class="deckname" href="<?php echo $mastercard->getDeck()->getDeckpageUrl(); ?>"><?php echo $mastercard->getDeck()->getDeckname(); ?></a>
    	<br>
    	<small><?php echo $mastercard->getDate($this->login->getUser()->getTimezone()); ?></small>
    </span>
<?php } ?>
</div>

<?php if(count($cat_elements) == 0){ $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>