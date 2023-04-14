<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('admin_dashboard')?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('deck_update')?>">Updates</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Update bearbeiten</h1>

<form method="POST" action="">
		<h4> Decks entfernen</h4>
	<div class="row">
	<?php foreach($update->getRelatedDecks() as $deck){ ?>
		<div class="col-12 col-md-6 col-lg-4 text-center">
    		<div><?php echo $deck->getMasterCard(); ?></div>
    		<p><input type="checkbox" name="remove_decks[]" value="<?php echo $deck->getId();?>">
    			<span class="deckname"><?php echo $deck->getDeckname();?></span></p>
		</div>
	<?php } ?>
	</div>
		<h4>Decks hinzufügen</h4>
	<div class="row">
	<?php foreach($update->getUnlinkedDecks() as $deck){ ?>
		<div class="col-12 col-md-6 col-lg-4 text-center">
    		<div><?php echo $deck->getMasterCard(); ?></div>
    		<p><input type="checkbox" name="add_decks[]" value="<?php echo $deck->getId();?>">
    			<span class="deckname"><?php echo $deck->getDeckname();?></span></p>
		</div>
	<?php } ?>
	</div>

	<p class="text-center">
		<a class="btn btn-dark" href="<?php echo RoutesDb::getUri('deck_update');?>">zurück zur Liste</a> 
		<?php if(!$update->isPublic()){ ?>
		&bull; <input class="btn btn-primary" type="submit" name="editUpdate" value="speichern"></p>
		<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		<?php } ?>
</form>