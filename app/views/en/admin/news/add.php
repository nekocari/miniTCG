<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('news_index');?>">News</a></li>
    <li class="breadcrumb-item active" aria-current="page">write</li>
  </ol>
</nav>

<h1>Write News</h1>

<form method="POST" action="">
	
<table class="table table-striped">
	<tbody>
		<tr>
			<td>Titlw</td>
			<td><input class="form-control" type="text" name="title" required></td>
		</tr>
		<tr>
			<td>Text</td>
			<td><textarea class="form-control" name="text" rows="6" required></textarea>
				<div><small>You can use <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
				to format the text!</small></div>
			</td>
		</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('news_index');?>">go back</a> 
	&bull; <input class="btn btn-primary" type="submit" name="addNews" value="save">
</p>



</form>