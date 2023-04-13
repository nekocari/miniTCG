<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('news_index');?>">News</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>News bearbeiten</h1>

<form method="POST" action="">
	
<table class="table table-striped">
	<tbody>
		<tr>
			<td>Titel</td>
			<td><input class="form-control" type="text" name="title" value="<?php echo $entry->getTitle(); ?>"></td>
		</tr>
		<tr>
			<td>Text</td>
			<td><textarea class="form-control" name="text" rows="6"><?php echo $entry->getTextPlain(); ?></textarea>
				<div><small>Du kannst <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
				verwenden um den Text zu formatieren!</small></div>
			</td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo RoutesDb::getUri('news_index');?>">zurück zur Liste</a> 
	&bull; <input class="btn btn-primary" type="submit" name="updateNews" value="speichern">
	<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">
</p>



</form>