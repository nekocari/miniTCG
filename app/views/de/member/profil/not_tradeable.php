
<div class="my-4">
<?php if(isset($cat_elements) AND count($cat_elements) ){ foreach($cat_elements as $card){ ?>
    <div class="d-inline-block card-member-profil">
    <?php echo $card->getImageHtml(); ?>
    	<?php if($card->getPossessionCounter() > 1){ ?>
    		<span class="badge badge-dark"><?php echo $card->getPossessionCounter(); ?></span>
    	<?php } ?>
	</div>
<?php } }else { $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>
</div>


<?php include $this->partial('templates/card_color_legend.php'); ?>