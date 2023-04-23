<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Deck Arten</li>
  </ol>
</nav>

<h1>Deck Arten</h1>

<p>Individualisiere die Deck Arten deines TCG<p>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Name</th>
    		<th class="text-center">Karten</th>
    		<th class="text-center">Ansichts Template?</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($deck_types as $entry){ ?>
    	<tr style="white-space:nowrap">
    		<td><?php echo $entry->getId(); ?></td>
    		<td><?php echo $entry->getName(); ?></td>
    		<td class="text-center"><?php echo $entry->getSize(); ?></td>
    		<td class="text-center"><?php if(empty($entry->getTemplatePath())){ ?> <i class="fas fa-minus-circle text-secondary"></i> <?php }else{ ?> <i class="fas fa-check-circle text-primary"></i> <?php } ?></td>
    		<td>
    			<form class="text-right form-inline justify-content-end" method="POST" action="">
    				<button class="btn btn-danger btn-sm del-link mx-1" onclick="return confirm('Eintrag <?php echo $entry->getName(); ?> wirklich löschen?');">
    					<i class="fas fa-times"></i> <span class="d-none d-md-inline">löschen</span></button>
    				<input type="hidden" name="action" value="del_deck_type">
    				<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">
    				
        			<a class="btn btn-sm btn-primary" href="<?php echo Routes::getUri('admin_deck_type_edit');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>

<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('admin_deck_type_add');?>">neue Deck Art anlegen</a></p>