<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kategorien</li>
  </ol>
</nav>


<p class="text-left"><a href="<?php echo Routes::getUri('category_add');?>" class="btn btn-primary">Kategorie anlegen</a></p>


<?php foreach($categories as $category){ ?>
<div class="card mx-auto my-4">
	<div class="card-header d-flex justify-content-between align-items-center">
		<?php echo $category->getName(); ?>
		<div class="row">
			<form class="col px-1 m-0" method="POST" action="">
				<button class="btn btn-danger btn-sm del-link" onclick="return confirm('Kategorie <?php echo $category->getName(); ?> wirklich löschen?');">
					<i class="fas fa-times"></i> <span class="d-none d-md-inline">löschen</span></button>
				<input type="hidden" name="action" value="del_cat">
				<input type="hidden" name="id" value="<?php echo $category->getId(); ?>">
			</form>
			<a class="btn btn-primary btn-sm" href="<?php echo Routes::getUri('category_edit');?>?id=<?php echo $category->getId(); ?>">
				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
		</div>
	</div>
	
	<ul class="list-group list-group-flush m-0">
		<?php if(isset($subcategories[$category->getId()])){ foreach($subcategories[$category->getId()] as $subcategory){ ?>
		<li class="list-group-item d-flex justify-content-between align-items-center">
			<?php echo $subcategory->getName(); ?>
			<div class="row">
    			<form class="col px-1 m-0" method="POST" action="">
    				<button class="btn btn-danger btn-sm del-link" onclick="return confirm('Unterkategorie <?php echo $subcategory->getName(); ?> wirklich löschen?');">
    					<i class="fas fa-times"></i> <span class="d-none d-md-inline">löschen</span></button>
    				<input type="hidden" name="action" value="del_subcat">
    				<input type="hidden" name="id" value="<?php echo $subcategory->getId(); ?>">
    			</form>
    			<a class="btn btn-primary btn-sm" href="<?php echo Routes::getUri('subcategory_edit');?>?id=<?php echo $subcategory->getId(); ?>">
    				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span></a>
			</div>
		</li>
		<?php } } ?>
		<li class="list-group-item">
			<form method="POST" action="<?php echo Routes::getUri('subcategory_add');?>?id=<?php echo $category->getId(); ?>">
				<input type="hidden" name="category" value="<?php echo $category->getId(); ?>">
        		<button class="btn btn-primary btn-sm">Unterkategorie anlegen</button>
        	</form>
        </li>
	</ul>

</div>
<?php } ?>