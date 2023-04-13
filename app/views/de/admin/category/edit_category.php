<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo RoutesDb::getUri('category_index');?>">Kategorien</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kategorie Bearbeiten</li>
  </ol>
</nav>

<h1>Kategorie bearbeiten</h1>

<form class="form-inline" method="POST" action="">
	<input class="form-control m-2" type="text" name="name" pattern="[A-Za-z0-9äÄöÖüÜß _\-]+" value="<?php echo $category->getName(); ?>">
	<input class="btn btn-primary" type="submit" name="rename" value="speichern">
</form>
<small>Erlaubt sind Buchstaben, Zahlen, sowie Leerzeichen, "-" und "_"</small>