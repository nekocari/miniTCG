<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_card_status_index');?>">Manager Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">add</li>
  </ol>
</nav>

<h1>Add Manager Category</h1>


<form method="POST" action="">
<div style="table-responsive">
<table class="table table-striped">
	<tbody>
    	<tr>
    		<td>Name</td>
    		<td><input class="form-control" type="text" name="name" required></td>
    	</tr>
	</tbody>
</table>
</div>
<p class="text-center">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_card_status_index');?>">go back</a> &bull;
	<input class="btn btn-primary" type="submit" name="addCardStatus" value="save">
</p>
</form>