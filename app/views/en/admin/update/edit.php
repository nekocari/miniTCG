<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard')?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('deck_update')?>">Updates</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Update</h1>

<form method="POST" action="">
		<h4>Remove Decks</h4>
	<div class="row">
	<?php foreach($update->getRelatedDecks() as $deck){ ?>
		<div class="col-12 col-md-6 col-lg-4 text-center">
    		<label for="check<?php echo $deck->getId();?>"><?php echo $deck->getMasterCard(); ?></label>
    		<p>
    			<label for="check<?php echo $deck->getId();?>">
    				<input id="check<?php echo $deck->getId();?>" type="checkbox" name="remove_decks[]" value="<?php echo $deck->getId();?>">
    				<span class="deckname"><?php echo $deck->getDeckname();?></span>
    				&bull; <span class="text-primary"><?php echo $deck->getVoteCount(); ?> <i class="small fas fa-thumbs-up"></i></span>
    			</label>
    		</p>
		</div>
	<?php } ?>
	</div>
		<h4>Add Decks</h4>
	<div class="row">
	<?php foreach($update->getUnlinkedDecks() as $deck){ ?>
		<div class="col-12 col-md-6 col-lg-4 text-center">
    		<label for="check<?php echo $deck->getId();?>"><?php echo $deck->getMasterCard(); ?></label>
    		<p>
    			<label for="check<?php echo $deck->getId();?>">
    				<input id="check<?php echo $deck->getId();?>" type="checkbox" name="add_decks[]" value="<?php echo $deck->getId();?>">
    				<span class="deckname"><?php echo $deck->getDeckname();?></span>
    				&bull; <span class="text-primary"><?php echo $deck->getVoteCount(); ?> <i class="small fas fa-thumbs-up"></i></span>
    			</label>
    		</p>
		</div>
	<?php } ?>
	</div>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('deck_update');?>">go back</a> 
		<?php if(!$update->isPublic()){ ?>
		&bull; <input class="btn btn-primary" type="submit" name="editUpdate" value="save"></p>
		<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		<?php } ?>
</form>