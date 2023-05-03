<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('category_index');?>">Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit Category</li>
  </ol>
</nav>

<h1>Edit Category</h1>

<form class="form-inline" method="POST" action="">
	<input class="form-control m-2" type="text" name="name" pattern="[A-Za-z0-9äÄöÖüÜß _\-]+" value="<?php echo $category->getName(); ?>">
	<input class="btn btn-primary" type="submit" name="rename" value="save">
</form>
<small>Letters, numbers and spaces, "-" and "_" are allowed</small>

<p class="text-center m-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('category_index');?>">go back</a>
</p>