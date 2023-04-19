<div class="my-4">

<?php if(isset($cat_elements) AND count($cat_elements) ){  ?>
    <div class="alert alert-info" role="alert">
    	Klicke auf eine Karte um <?php echo $member->getName(); ?> ein Tauschangebot zu machen!
    </div>
    <?php foreach($cat_elements as $card){ ?>
        <div class="d-inline-block card-member-profil <?php if($card->missingInKeep()){ echo " card-missing-keep"; } 
        if($card->missingInCollect()){ echo " card-missing-collect"; } if($card->mastered()){ echo " card-mastered"; } ?>">
        	<a href="<?php echo Routes::getUri('trade').'?card='.$card->getId(); ?>">
            	<?php echo $card->getImageHtml(); ?>
        	</a>
        	<?php if($card->getPossessionCounter() > 1){ ?>
    		<span class="badge badge-dark"><?php echo $card->getPossessionCounter(); ?></span>
    		<?php } ?>
    </div>
	<?php } ?>
<?php }else { $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>
</div>

<div class="text-center my-2">
	<small>
		<span class="d-inline-block card-member-profil card-missing-keep">fehlend in nicht tauschbar</span>
		<span class="d-inline-block card-member-profil card-missing-collect">fehlend in Sammlung</span>
		<span class="d-inline-block card-member-profil card-mastered">Deck gemastert</span>
	</small>
</div>