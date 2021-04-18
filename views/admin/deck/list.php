<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Karten</li>
  </ol>
</nav>

<h1>Liste aller Karten</h1>

<div class="table-responsive">
<table class="table table-striped table-sm">
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
		<tr style="white-space:nowrap">
			<td><?php echo $deck->getId(); ?></td>
			<td>
				<span class="deckname"><?php echo $deck->getDeckname(); ?></span>
				<span class="badge <?php echo $badge_css[$deck->getStatus()]?>"><?php echo ucfirst($deck->getStatus()); ?></span><br>
				<small><?php echo $deck->getName(); ?></small>
			</td>
			<td><?php echo $deck->getCreatorName(); ?><br><small><?php echo $deck->getDate(); ?></small></td>
			<td class="text-right">
    			<a class="btn btn-sm btn-primary" href="<?php echo ROUTES::getUri('deck_edit');?>?id=<?php echo $deck->getId(); ?>">
    				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
</div>