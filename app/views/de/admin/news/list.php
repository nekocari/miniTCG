<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">News</li>
  </ol>
</nav>

<h1>News Übersicht</h1>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Titel<br>
 				<small>Datum &bull; Autor</small></th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($news as $entry){ ?>
    	<tr>
    		<td><?php echo $entry->getId(); ?></td>
    		<td><?php echo $entry->getTitle(); ?><br>
    			<small><b><?php echo $entry->getDate($this->login->getUser()->getTimezone()); ?></b> &bull; <?php echo $entry->getAuthor()->getName(); ?></small></td>
    		<td style="white-space:nowrap">
    			<form class="text-right" method="POST" action="">
    				<button class="btn btn-danger btn-sm del-link mx-1" onclick="return confirm('Eintrag <?php echo $entry->getTitle(); ?> wirklich löschen?');">
    					<i class="fas fa-times"></i> <span class="d-none d-md-inline">löschen</span></button>
    				<input type="hidden" name="action" value="del_news">
    				<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">
    				
        			<a class="btn btn-sm btn-dark" href="<?php echo Routes::getUri('news_link_update');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-plus"></i> <span class="d-none d-md-inline">Update</span></a>
    				
        			<a class="btn btn-sm btn-primary" href="<?php echo Routes::getUri('news_edit');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php echo $pagination; ?>