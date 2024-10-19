<div class="mt-2">

<div class="text-end mb-2">
	<?php if(!isset($_GET['filter'])){ ?>
		<a href="<?php echo $member->getProfilLink().'&cat='.$cat->getId().'&filter=needed'; ?>" class="btn btn-sm btn-outline-secondary">nur benötigte Karten</a>
	<?php }else{ ?>
		<a href="<?php echo $member->getProfilLink().'&cat='.$cat->getId(); ?>" class="btn btn-sm btn-outline-secondary">Filter aufheben</a>
	<?php } ?>
</div>

<?php if(isset($cat_elements) AND count($cat_elements) ){  ?>
    <div class="alert alert-info" role="alert">
    	Klicke auf eine Karte um <?php echo $member->getName(); ?> ein Tauschangebot zu machen!
    </div>
    <?php foreach($cat_elements as $card){ ?>
        <div class="d-inline-block card-member-profil <?php 
        if($card->missingInNotTradeable() AND !$card->owned()){ echo " card-missing-keep"; }
        if($card->missingInCollect() AND !$card->owned()){ echo " card-missing-collect"; }
        if($card->onWishlist() AND !$card->owned()){ echo " card-missing-wishlist"; } 
        if($card->mastered()){ echo " card-mastered"; } 
        ?>">
        	<a href="<?php echo Routes::getUri('trade').'?card='.$card->getId(); ?>">
            	<?php echo $card->getImageHtml(); ?>
        	</a>
        	<?php if($card->getPossessionCounter() > 1){ ?>
    		<span class="badge bg-dark"><?php echo $card->getPossessionCounter(); ?></span>
    		<?php } ?>
    </div>
	<?php } ?>
<?php }else { $this->renderMessage('info','Keine Karten in dieser Kategorie'); } ?>
</div>


<?php include $this->partial('templates/card_color_legend.php'); ?>