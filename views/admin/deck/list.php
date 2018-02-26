<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="admin/">Karten</a></li>
    <li class="breadcrumb-item active" aria-current="page">Liste</li>
  </ol>
</nav>

<h1>Liste aller Karten</h1>

<table class="table table-striped">
	<thead>
		<tr>
    		<th>ID</th>
    		<th>Kürzel<br><small>Name</small></th>
    		<th>Ersteller<br><small>Datum</small></th>
    		<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($carddecks as $deck){ ?>
		<tr>
			<td><?php echo $deck->getId(); ?></td>
			<td>
				<span class="deckname"><?php echo $deck->getDeckname(); ?></span>
				<span class="badge <?php echo $badge_css[$deck->getStatus()]?>"><?php echo ucfirst($deck->getStatus()); ?></span><br>
				<small><?php echo $deck->getName(); ?></small>
			</td>
			<td><?php echo $deck->getCreatorName(); ?><br><small><?php echo $deck->getDate(); ?></small></td>
			<td>
				<form method="post" action="admin/deck/edit.php?id=<?php echo $deck->getId(); ?>" class="text-right">
    				<button class="btn btn-link"><i class="fas fa-pencil-alt"></i> bearbeiten</button>
    			</form>	
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>