<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('admin_dashboard')?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('deck_update')?>">Updates</a></li>
    <li class="breadcrumb-item active" aria-current="page">anlegen</li>
  </ol>
</nav>

<h1>Update anlegen</h1>

<form method="POST" action="">
	<div class="row">
	<?php foreach($new_decks as $deck){ ?>
		<div class="col-12 col-md-6 col-lg-4 text-center">
    		<div><?php echo $deck->getMasterCard(); ?></div>
    		<p><input type="checkbox" name="decks[]" value="<?php echo $deck->getId();?>">
    			<span class="deckname"><?php echo $deck->getDeckname();?></span></p>
		</div>
	<?php } ?>
	</div>
	<p class="text-center"><input class="btn btn-primary" type="submit" name="addUpdate" value="Update anlegen"></p>
</form>