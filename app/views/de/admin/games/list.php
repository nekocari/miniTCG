<?php 
/*
 * preset variables:
 * $list - a Listable object containing GameSetting objects as items
 * 
 */
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Spiele</li>
  </ol>
</nav>

<h1>Spiele</h1>


<form method="GET" action="">
	<div class="row">
		<div class="col-12 col-md">
			<div class="input-group input-group-sm">
				<select class="form-select" name="order">
					<option value="id" <?php if(isset($_GET['order']) AND $_GET['order']=='id'){ echo 'selected'; } ?>>ID</option>
					<option value="type" <?php if(isset($_GET['order']) AND $_GET['order']=='type'){ echo 'selected'; } ?>>Art</option>
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
				<input class="form-control" type="text" name="search" list="game-list-data" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; } ?>">
				<datalist id="game-list-data">
					<?php foreach(GameSetting::getAll() as $gs){ ?>
						<option><?php echo $gs->getName('de'); ?></option>
					<?php } ?>
				</datalist>
				<button class="btn btn-dark"><i class="fas fa-search "></i></button>
			</div>
		</div>
	</div>
</form>

<form method="POST" action="">
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
		    	<tr>
		    		<th>Name</th>
		    		<th class="text-center">Status</th>
		    		<th class="text-center">Art</th>
		    		<th class="text-center">Wartezeit</th>
		    		<th></th>
		    	</tr>
			</thead>
			<tbody>
		<?php foreach($list->getItems() as $game){ ?>
		    	<tr style="white-space:nowrap">
		    		<td><?php echo $game->getName('de'); ?></td>
		    		<td class="text-center"><span class="badge text-bg-light"><?php echo $game->getStatus(); ?></span></td>
		    		<td class="text-center"><?php echo $game->getType(); ?></td>
		    		<td class="text-center"><?php if(!$game->isDailyGame()){ echo $game->getWaitTime().' min'; }else{ echo '-'; } ?></td>
		    		<td class="text-right">
		    			<a href="<?php echo Routes::getUri('admin_games_edit');?>?id=<?php echo $game->getId(); ?>" class="btn btn-sm btn-primary">
							<i class="fas fa-pencil-alt"></i> 
							<span class="d-none d-md-inline">bearbeiten</span></a>
		    			<button class="btn btn-sm btn-danger" name="delete_game" value="<?php echo $game->getId(); ?>" 
		    			onclick="return confirm('Spiel <?php echo $game->getName('de'); ?> wirklich löschen?')"><i class="fas fa-times"></i> 
							<span class="d-none d-md-inline">löschen</span></button>
		    		</td>
		    	</tr>
		<?php } ?>
			</tbody>
		</table>
	</div>
</form>

<?php echo $list->getPagination()->getPaginationHTML(); ?>

<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('admin_games_add');?>">neues Spiel anlegen</a></p>