<h1>Karten Decks</h1>

<?php if($list->getDeckStatus() == 'new'){ ?>
	<h2>Unveröffentlicht</h2>
<?php } ?>

<?php if($list->getCount() > 0) { ?>
<div class="table-responsive">
	<table class="table">
		<thead class="table-light">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Kategorie</th>
				<th>Erstellt</th>
			</tr>
		</thead>
    	<?php foreach($list->getItems() as $deck){ ?>
    	<tr>
    		<th>#<?php echo $deck->getId(); ?></th>
    		<td><a class="deckname" href="<?php echo $deck->getDeckpageUrl(); ?>"><?php echo $deck->getDeckname(); ?></a><br>
    			<small><?php echo $deck->getName(); ?></small></td>
    		<td><?php echo $deck->getCategoryName(); ?> - <?php echo $deck->getSubcategoryName(); ?></td>
    		<td><?php if($this->login->isloggedIn()){ echo $deck->getDate($this->login->getUser()->getTimezone()); } else { echo $deck->getDate(); } ?><br>
    			<small>von <?php echo $deck->getCreatorName(); ?></small></td>
    	</tr>
    	<?php } ?>
    </table>
</div>

<?php echo $list->getPagination()->getPaginationHTML(); ?>

<?php }else{ echo $this->renderMessage('info','Es existieren keine veröffentlichten Decks.'); } ?>