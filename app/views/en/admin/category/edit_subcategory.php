<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('category_index');?>">Categories</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('category_index');?>"><?php echo $category->getName(); ?></a>
    <li class="breadcrumb-item active" aria-current="page">edit Subcategory</li>
  </ol>
</nav>

<h1>Edit Subcategory</h1>

<form class="form-inline" method="POST" action="">
	<select class="form-control m-2" name="category">
		<?php foreach($categories as $cat){
		    echo '<option value="'.$cat->getId().'"';
		    if($cat->getId() == $category->getId()){ echo 'selected'; }
		    echo '>'.$cat->getName().'</option>';
		} ?>
	</select>
	<input class="form-control m-2" type="text" name="name" pattern="[A-Za-z0-9äÄöÖüÜß _\-]+" value="<?php echo $subcategory->getName(); ?>">
	<input class="btn btn-primary" type="submit" name="rename" value="save">
</form>
<small>Letters, numbers and spaces, "-" and "_" are allowed</small>

<p class="text-center m-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('category_index');?>">go back</a>
</p>