<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Karten Kategorien</li>
  </ol>
</nav>

<h1>Karten Kategorien</h1>

<p>Individualiere die Kategorien zum Einsortieren im Kartenmanager<p>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Name</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($card_categories as $entry){ ?>
    	<tr style="white-space:nowrap">
    		<td><?php echo $entry->getId(); ?></td>
    		<td><?php echo $entry->getName(); ?></td>
    		<td>
    			<form class="text-right form-inline" method="POST" action="">
    				<button class="btn btn-danger btn-sm del-link mx-1" onclick="return confirm('Eintrag <?php echo $entry->name(); ?> wirklich löschen?');">
    					<i class="fas fa-times"></i> <span class="d-none d-md-inline">löschen</span></button>
    				<input type="hidden" name="action" value="del_card_status">
    				<input type="hidden" name="id" value="<?php echo $entry->Id(); ?>">
    				
    				
        			<a class="btn btn-sm btn-primary" href="<?php echo RoutesDb::getUri('admin_card_status_edit');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>