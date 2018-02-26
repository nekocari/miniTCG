<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="admin/cards/list.php">Karten</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Deck bearbeiten</h1>

<h2 class="deckname"><?php echo $deckdata->getDeckname(); ?></h2>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
    	<tr>
    		<td>ID</td>
    		<td><input class="form-control" type="text" name="id" value="<?php echo $deckdata->getId(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Status</td>
    		<td><input class="form-control" type="text" name="status" value="<?php echo $deckdata->getStatus(); ?>" disabled></td>
    	</tr>
    	<tr>
    		<td>Name</td>
    		<td><input class="form-control" type="text" name="name" value="<?php echo $deckdata->getName(); ?>"></td>
    	</tr>
    	<tr>
    		<td>Ersteller</td>
    		<td>
				<select class="form-control" name="creator">
					<?php 
					foreach($memberlist as $member){
					   echo '<option value="'.$member->id.'"';
					   if($member->id == $deckdata->getCreator()){ echo " selected"; }
					   echo '>'.$member->name.' </option>';
					} 
					?>
				</select>
			</td>
    	</tr>
    	<tr>
    		<td>Kategorie</td>
    		<td>
        		<select class="form-control" id="category" name="subcategory" required>
        			<?php foreach($categories as $category){ ?>
        			<optgroup label="<?php echo $category->getName(); ?>">
        				<?php foreach($subcategories[$category->getId()] as $subcategory){
        				    echo '<option value="'.$subcategory->getId().'"';
        				    if($subcategory->getId() == $deckdata->getSubcategory()){ echo " selected"; }
        				    echo '>'.$subcategory->getName().'</option>';
        				} ?>
        			</optgroup>
        			<?php } ?>
        		</select>
           	</td>
    	</tr>
    	<tr>
    		<td>Karten</td>
    		<td>
    			<?php foreach($card_images as $key => $image){ if($key == 'master'){ echo "<br>"; } echo $image;  } ?>
        		<div class="text-right">
        			<a href="admin/cards/replace.php?id=<?php echo $deckdata->getId(); ?>" class="btn btn-dark btn-sm">eine Karte ersetzen</a>
        		</div>
    		</td>
    	</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="admin/cards/list.php">zurück zur Liste</a> &bull;
	<input class="btn btn-primary" type="submit" name="updateDeckdata" value="speichern">
	<input type="hidden" name="id" value="<?php echo $deckdata->getId(); ?>">
</p>
</form>