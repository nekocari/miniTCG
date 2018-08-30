<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">News</li>
  </ol>
</nav>

<h1>News Übersicht</h1>


<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Datum</th>
    		<th>Titel</th>
    		<th>Autor</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($news as $entry){ ?>
    	<tr>
    		<td><?php echo $entry->getId(); ?></td>
    		<td><?php echo $entry->getDate(); ?></td>
    		<td><?php echo $entry->getTitle(); ?></td>
    		<td><?php echo $entry->getAuthor()->getName(); ?></td>
    		<td>
    			<form class="text-right" method="POST" action="">
    				<button class="btn btn-danger btn-sm del-link mx-1" onclick="return confirm('Eintrag <?php echo $entry->getTitle(); ?> wirklich löschen?');">
    					<i class="fas fa-times"></i> löschen</button>
    				<input type="hidden" name="action" value="del_news">
    				<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">
    				
        			<a class="btn btn-sm btn-primary" href="<?php echo ROUTES::getUri('news_edit');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-pencil-alt"></i> bearbeiten</a>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>

<?php echo $pagination; ?>