<?php 
/*
 * preset variables:
 * $deckdata -> Carddeck class
 * 
 */
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_type_index');?>">Deck Arten</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Deck Art bearbeiten</h1>

<h2><?php echo $deck_type->getName(); ?></h2>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
    	<tr>
    		<td>ID</td>
    		<td><input class="form-control" type="text" name="id" value="<?php echo $deck_type->getId(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Name</td>
    		<td><input class="form-control" type="text" name="name" value="<?php echo $deck_type->getName(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Anzahl Karten</td>
    		<td><input class="form-control" type="number" name="size" value="<?php echo $deck_type->getSize(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Template Dateipfad</td>
    		<td><input class="form-control" type="text" name="template_path" value="<?php echo $deck_type->getTemplatePath(); ?>"></td>
    	</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_deck_type_index');?>">zurück zur Liste</a> &bull;
	<input class="btn btn-primary" type="submit" name="updateDeckType" value="speichern">
	<input type="hidden" name="id" value="<?php echo $deck_type->getId(); ?>">
</p>
</form>