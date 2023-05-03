<h1>Messages</h1>

<div class="text-right"><a class="btn btn-primary" href="<?php echo Routes::getUri('messages_write')?>">new message</a></div>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link" href="<?php echo Routes::getUri('messages_recieved')?>">inbox</a></li>
	<li class="nav-item"><a class="nav-link active" href="<?php echo Routes::getUri('messages_sent')?>">outbox</a></li>
</ul>


<?php foreach($msgs as $msg){ ?>
<div class="card my-4">
	<div class="card-header">
		<div class="row">
    		<div class="col">
    			<a href="<?php echo $msg->getRecipient()->getProfilLink(); ?>"><?php echo $msg->getRecipient()->getName(); ?></a> 
    			&bull; <?php echo $msg->getDate($this->login->getUser()->getTimezone()); ?>
    		</div>
    		<div class="col text-right">
        		<form class="m-0 p-0" method="POST" action="">
        			<?php if($msg->isNew()){ ?>
        			<button disabled class="btn btn-sm btn-secondary"><small>unread</small></button>
        			<?php }else{ ?>
        			<button class="btn btn-sm" disabled><small>read</small></button>
        			<?php } ?>
        			<button class="btn btn-sm btn-danger" name="delete" value="<?php echo $msg->getId(); ?>"><small>delete</small></button>
        		</form>
        	</div>
    	</div>
	</div>
	<div class="card-body"><?php echo $msg->getText(); ?></div>
</div>
<?php } ?>

<?php echo $pagination; ?>

<div class="my-4"><?php if(count($msgs) == 0){ $this->renderMessage('info','no messages'); } ?></div>