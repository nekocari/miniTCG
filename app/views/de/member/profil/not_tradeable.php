<?php if(isset($cat_elements) AND count($cat_elements) ){  ?>
<div class="my-4">
<?php foreach($cat_elements as $card){ ?>
    <div class="d-inline-block card-member-profil">
    <?php echo $card->getImageHtml(); ?>
    	<?php if($card->getPossessionCounter() > 1){ ?>
    		<span class="badge badge-dark"><?php echo $card->getPossessionCounter(); ?></span>
    	<?php } ?>
	</div>
<?php } ?>
</div>

<?php }else { $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>

<div class="text-center my-2">
	<small>
		<span class="d-inline-block card-member-profil card-missing-keep">fehlend in nicht tauschbar</span>
		<span class="d-inline-block card-member-profil card-missing-collect">fehlend in Sammlung</span>
		<span class="d-inline-block card-member-profil card-mastered">Deck gemastert</span>
	</small>
</div>