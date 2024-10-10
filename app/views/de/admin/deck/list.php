<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Karten</li>
  </ol>
</nav>

<h1>Liste aller Karten</h1>


<form method="GET" action="">
	<div class="row">
		<div class="col-12 col-md">
			<div class="input-group input-group-sm">
				<select class="form-select" name="order">
					<option value="id" <?php if(isset($_GET['order']) AND $_GET['order']=='id'){ echo 'selected'; } ?>>ID</option>
					<option value="name" <?php if(isset($_GET['order']) AND $_GET['order']=='name'){ echo 'selected'; } ?>>Name</option>
					<option value="deckname" <?php if(isset($_GET['order']) AND $_GET['order']=='deckname'){ echo 'selected'; } ?>>Kürzel</option>
					<option value="status" <?php if(isset($_GET['order']) AND $_GET['order']=='status'){ echo 'selected'; } ?>>Status</option>
				</select>
				<select class="form-select" name="direction">
					<option value="ASC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='ASC'){ echo 'selected'; } ?>>aufsteigend</option>
					<option value="DESC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='DESC'){ echo 'selected'; } ?>>absteigend</option>
				</select>
				<button class="btn btn-dark"><i class="fas fa-exchange-alt fa-rotate-90"></i></button>
			</div>
		</div>
		<div class="col-12 col-md py-md-0 py-1">
			<div class="input-group input-group-sm">
				<input class="form-control" type="text" name="search" list="deck-list-data" value="<?php echo $list->getSearchStr(); ?>">
				<datalist id="deck-list-data">
					<?php foreach(Carddeck::getAll() as $deck){ ?>
						<option><?php echo $deck->getName(); ?></option>
						<option><?php echo $deck->getDeckname(); ?></option>
					<?php } ?>
				</datalist>
				<button class="btn btn-dark"><i class="fas fa-search "></i></button>
			</div>
		</div>
	</div>
</form>

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
	<?php foreach($list->getItems() as $deck){ ?>
		<tr style="white-space:nowrap">
			<td><?php echo $deck->getId(); ?></td>
			<td>
				<span class="deckname"><?php echo $deck->getDeckname(); ?></span>
				<span class="badge <?php echo $badge_css[$deck->getStatus()]?>"><?php echo ucfirst($deck->getStatus()); ?></span><br>
				<small><?php echo $deck->getName(); ?></small>
			</td>
			<td><?php echo $deck->getCreatorName(); ?><br>
				<small><?php echo $deck->getDate($this->login->getUser()->getTimezone()); ?></small>
			</td>
			<td class="text-right">
    			<a class="btn btn-sm btn-primary" href="<?php echo Routes::getUri('deck_edit');?>?id=<?php echo $deck->getId(); ?>">
    				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
</div>

<?php echo $list->getPagination()->getPaginationHTML(); ?>