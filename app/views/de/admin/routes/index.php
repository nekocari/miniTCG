<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Routing</li>
  </ol>
</nav>

<h1>Routing Übersicht</h1>

<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('admin_routes_add');?>">neue Route anlegen</a></p>

<form method="GET" action="">
	<div class="row">
		<div class="col-12 col-md">
			<div class="input-group input-group-sm">
				<select class="form-control" name="order">
					<option value="identifier" <?php if(isset($_GET['order']) AND $_GET['order']=='identifier'){ echo 'selected'; } ?>>Identifier</option>
					<option value="url" <?php if(isset($_GET['order']) AND $_GET['order']=='url'){ echo 'selected'; } ?>>URL</option>
				</select>
				<select class="form-control" name="direction">
					<option value="ASC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='ASC'){ echo 'selected'; } ?>>aufsteigend</option>
					<option value="DESC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='DESC'){ echo 'selected'; } ?>>absteigend</option>
				</select>
				<button class="btn btn-dark"><i class="fas fa-exchange-alt fa-rotate-90"></i></button>
			</div>
		</div>
		<div class="col-12 col-md py-md-0 py-1">
			<div class="input-group input-group-sm">
				<input class="form-control" type="text" name="search" list="route-list-data" value="<?php echo $list->getSearchStr(); ?>">
				<datalist id="route-list-data">
					<?php foreach(Route::getAll() as $route){ ?>
						<option><?php echo $route->getIdentifier(); ?></option>
					<?php } ?>
				</datalist>
				<button class="btn btn-dark"><i class="fas fa-search "></i></button>
			</div>
		</div>
	</div>
</form>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>Identifier/Url</th>
    		<th>Controller/Action</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($list->getItems() as $entry){ ?>
    	<tr style="white-space:nowrap">
    		<td><?php echo $entry->getIdentifier(); ?><br>
    		<small><?php echo $entry->getUrl(); ?></small></td>
    		<td><?php echo $entry->getController(); ?>-><?php echo $entry->getAction(); ?></td>
    		<td>
    			<form class="justify-content-end form-inline" method="POST" action="">
    				<?php if($entry->isDeletable()){ ?>
	    				<button class="btn btn-danger btn-sm del-link mx-1" onclick="return confirm('Eintrag <?php echo $entry->getIdentifier(); ?> wirklich löschen?');">
	    					<i class="fas fa-times"></i> <span class="d-none d-md-inline">löschen</span></button>
	    				<input type="hidden" name="action" value="del_route">
    				<?php }else{ ?>
    					<button class="btn btn-secondary btn-sm del-link mx-1" disabled><i class="fas fa-times"></i> <span class="d-none d-md-inline">unlöschbar</span></button>
    				<?php } ?>
    				<input type="hidden" name="id" value="<?php echo $entry->getIdentifier(); ?>">
    				
    				
        			<a class="btn btn-sm btn-primary" href="<?php echo Routes::getUri('admin_routes_edit');?>?id=<?php echo $entry->getIdentifier(); ?>">
        				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>

<?php echo $list->getPagination()->getPaginationHTML(); ?>

<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('admin_routes_add');?>">neue Route anlegen</a></p>