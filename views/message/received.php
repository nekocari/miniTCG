<h1>Nachrichten</h1>


<?php foreach($msgs as $msg){ ?>
<div class="card my-4">
	<div class="card-header">
		<div class="row">
    		<div class="col">
    			<?php if($msg->isNew()){ ?><span class="badge badge-primary">New</span><?php } ?>
    			<?php echo $msg->getSender()->getName(); ?> &bull; <?php echo $msg->getDate(); ?>
    		</div>
    		<div class="col text-right">
        		<form class="m-0 p-0" method="POST" action="">
        			<?php if($msg->isNew()){ ?>
        			<button class="btn btn-sm btn-primary" name="read" value="<?php echo $msg->getId(); ?>"><small>gelesen markieren</small></button>
        			<?php }else{ ?>
        			<button class="btn btn-sm" disabled><small>gelesen</small></button>
        			<?php } ?>
        			<button class="btn btn-sm btn-dark" name="delete" value="<?php echo $msg->getId(); ?>"><small>löschen</small></button>
        		</form>
        	</div>
    	</div>
	</div>
	<div class="card-body"><?php echo $msg->getText(); ?><?php echo $msg->getStatus(); ?></div>
</div>
<?php } ?>

<?php echo $pagination; ?>