<h1>Karten Decks</h1>

<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Kategorie</th>
				<th>Erstellt</th>
			</tr>
		</thead>
    	<?php foreach($decks as $deck){ ?>
    	<tr>
    		<th>#<?php echo $deck->getId(); ?></th>
    		<td><a class="deckname" href="<?php echo $deck->getDeckpageUrl(); ?>"><?php echo $deck->getDeckname(); ?></a><br>
    			<small><?php echo $deck->getName(); ?></small></td>
    		<td><?php echo $deck->getCategoryName(); ?> - <?php echo $deck->getSubcategoryName(); ?></td>
    		<td><?php echo $deck->getDate(); ?><br>
    			<small>von <?php echo $deck->getCreatorName(); ?></small></td>
    	</tr>
    	<?php } ?>
    </table>
</div>

<?php echo $pagination; ?>