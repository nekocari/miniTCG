<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('news_index');?>">News</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit News</h1>

<form method="POST" action="">
	
<table class="table table-striped">
	<tbody>
		<tr>
			<td>Title</td>
			<td><input class="form-control" type="text" name="title" value="<?php echo $entry->getTitle(); ?>"></td>
		</tr>
		<tr>
			<td>Text</td>
			<td><textarea class="form-control" name="text" rows="6"><?php echo $entry->getTextPlain(); ?></textarea>
				<div><small>You can use <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
				to format the text!</small></div>
			</td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('news_index');?>">go back</a> 
	&bull; <input class="btn btn-primary" type="submit" name="updateNews" value="save">
	<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">
</p>



</form>