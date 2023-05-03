<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard')?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('deck_update')?>">Updates</a></li>
    <li class="breadcrumb-item active" aria-current="page">anlegen</li>
  </ol>
</nav>

<h1>Update anlegen</h1>

<form method="POST" action="">
	<div class="row">
	<?php foreach($new_decks as $deck){ ?>
		<div class="col-12 col-md-6 col-lg-4 text-center">
    		<label for="check<?php echo $deck->getId();?>"><?php echo $deck->getMasterCard(); ?></label>
    		<p>
    			<label for="check<?php echo $deck->getId();?>">
    				<input id="check<?php echo $deck->getId();?>" type="checkbox" name="decks[]" value="<?php echo $deck->getId();?>">
    				<span class="deckname"><?php echo $deck->getDeckname();?></span>
    				&bull; <span class="text-primary"><?php echo $deck->getVoteCount(); ?> <i class="small fas fa-thumbs-up"></i></span>
    			</label>
    		</p>
		</div>
	<?php } ?>
	</div>
	<p class="text-center"><input class="btn btn-primary" type="submit" name="addUpdate" value="Update anlegen"></p>
</form>