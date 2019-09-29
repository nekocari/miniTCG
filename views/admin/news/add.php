<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('news_index');?>">News</a></li>
    <li class="breadcrumb-item active" aria-current="page">schreiben</li>
  </ol>
</nav>

<h1>News schreiben</h1>

<form method="POST" action="">
	
<table class="table table-striped">
	<tbody>
		<tr>
			<td>Titel</td>
			<td><input class="form-control" type="text" name="title" required></td>
		</tr>
		<tr>
			<td>Text</td>
			<td><textarea class="form-control" name="text" rows="6" required></textarea>
				<div><small>Du kannst <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
				verwenden um den Text zu formatieren!</small></div>
			</td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo ROUTES::getUri('news_index');?>">zurück zur Liste</a> 
	&bull; <input class="btn btn-primary" type="submit" name="addNews" value="speichern">
</p>



</form>