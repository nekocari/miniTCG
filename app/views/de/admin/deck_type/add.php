<?php 
/*
 * preset variables:
 * $deck_type -> DeckTypeClass
 * 
 */
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_type_index');?>">Deck Arten</a></li>
    <li class="breadcrumb-item active" aria-current="page">anlegen</li>
  </ol>
</nav>

<h1>Deck Art anlegen</h1>


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
    		<td>Karten Breite<br><small class="text-muted">Angabe in Pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="card_image_width" value="<?php echo $deck_type->getCardWidth(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Karten Höhe<br><small class="text-muted">Angabe in Pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="card_image_height" value="<?php echo $deck_type->getCardHeight(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Masterkarten Breite<br><small class="text-muted">Angabe in Pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="master_image_width" value="<?php echo $deck_type->getMasterWidth(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Masterkarten Höhe<br><small class="text-muted">Angabe in Pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="master_image_height" value="<?php echo $deck_type->getMasterHeight(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Platzhalter Variante<br></td>
    		<td>
    			<input type="radio" name="filler_type" value="identical" <?php if($deck_type->getFillerType() == 'identical'){ echo 'checked'; } ?> required> identisch <br>
    			<small class="text-muted">jede Kartennummer gleiche Grafik</small><br>
    			<input type="radio" name="filler_type" value="individual" <?php if($deck_type->getFillerType() == 'individual'){ echo 'checked'; } ?> required> individuell <br>
    			<small class="text-muted">unterschiedliche Grafik je Kartennummer</small>
    		</td>
    	</tr>
    	<tr>
    		<td>Platzhalter Dateipfad<br>
    		<small class="text-muted">individuel = Ordnerpfad / identisch = Dateipfad</small></td>
    		<td>
	    		<div class="input-group">
	        		<div class="input-group-prepend">
	    				<div class="input-group-text">public/img/cards/</div>
	    			</div>
		    		<input class="form-control" type="text" name="filler_path" value="<?php echo $deck_type->getFillerPath(); ?>" required>
		    	</div>
		    </td>
    	</tr>
    	<tr>
    		<td>Karten pro Reihe<br><small class="text-muted">falls keine Template benutz wird</small></td>
    		<td><input class="form-control" type="number" min="1" name="cards_per_row" value="<?php echo $deck_type->getPerRow(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Ansichts Template Dateipfad<br><small class="text-muted">optional</small></td>
    		<td>
	    		<div class="input-group">
	        		<div class="input-group-prepend">
	    				<div class="input-group-text">app/views/</div>
	    			</div>
    				<input class="form-control" type="text" name="template_path" placeholder="optional" value="<?php echo $deck_type->getTemplatePath(); ?>">
	    		</div>
	    	</td>
    	</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_deck_type_index');?>">zurück zur Liste</a> &bull;
	<input class="btn btn-primary" type="submit" name="addDeckType" value="speichern">
	<input type="hidden" name="id" value="<?php echo $deck_type->getId(); ?>">
</p>
</form>