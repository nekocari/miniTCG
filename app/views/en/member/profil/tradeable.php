<div class="mt-2">

<div class="text-end mb-2">
	<?php if(!isset($_GET['filter'])){ ?>
		<a href="<?php echo $member->getProfilLink().'&cat='.$cat->getId().'&filter=needed'; ?>" class="btn btn-sm btn-outline-secondary">needed cards only</a>
	<?php }else{ ?>
		<a href="<?php echo $member->getProfilLink().'&cat='.$cat->getId(); ?>" class="btn btn-sm btn-outline-secondary">remove filter</a>
	<?php } ?>
</div>

<?php if(isset($cat_elements) AND count($cat_elements) ){  ?>
    <div class="alert alert-info" role="alert">
    	Click on a card to send <?php echo $member->getName(); ?> a trade offer!
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
<?php }else { $this->renderMessage('info','no cards in this category'); } ?>
</div>


<?php include $this->partial('templates/card_color_legend.php'); ?>