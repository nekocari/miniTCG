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
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_index');?>">Karten</a></li>
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
    		<td>Typ</td>
    		<td>
    			<select class="form-select" name="type_id">
    				<?php 
    				foreach(DeckType::getAll() as $type){
    				    echo '<option value="'.$type->getId().'"';
    				    if($deckdata->getTypeId() == $type->getId()){ echo ' selected'; }
    				    echo '>'.$type->getName().' (Karten: '.$type->getSize().')</option>';
    				}
    				?>
    			</select>
    		</td>
    	</tr>
    	<tr>
    		<td>Ersteller</td>
    		<td>
				<select class="form-select" name="creator">
					<?php 
					foreach(Member::getAll(['name'=>'ASC']) as $member){
					   echo '<option value="'.$member->getId().'"';
					   if($member->getId() == $deckdata->getCreator()){ echo " selected"; }
					   echo '>'.$member->getName().' </option>';
					} 
					?>
				</select>
			</td>
    	</tr>
    	<tr>
    		<td>Kategorie</td>
    		<td>
        		<select class="form-select" id="category" name="subcategory" required>
        			<?php foreach(Category::getAll(['name'=>'ASC']) as $category){ ?>
        			<optgroup label="<?php echo $category->getName(); ?>">
        				<?php foreach($category->getSubcategories() as $subcategory){
        				    echo '<option value="'.$subcategory->getId().'"';
        				    if($subcategory->getId() == $deckdata->getSubcategory()->getId()){ echo " selected"; }
        				    echo '>'.$subcategory->getName().'</option>';
        				} ?>
        			</optgroup>
        			<?php } ?>
        		</select>
           	</td>
    	</tr>
    	<tr>
    		<td>Infotext</td>
    		<td>
            	<textarea class="form-control" name="description" placeholder="Infos zum Deck, wie z.B. Quellenangaben... (optional)"><?php echo $deckdata->getDescription('default'); ?></textarea>
                	<small class="text-muted">Du kannst 
                		<a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
                		zur Formatierung nutzen!</small>
			</td>
    	</tr>
	</tbody>
</table>

<h2>Karten</h2>
<div class="text-center table-responsive text-nowrap">
<?php echo $deckdata->getDeckView().'<br>'.$deckdata->getMasterCard(); ?>
</div>
<div class="text-right">
	<a href="<?php echo Routes::getUri('card_replace_image');?>?deck_id=<?php echo $deckdata->getId(); ?>" class="btn btn-dark btn-sm">eine Karte ersetzen</a>
</div>
    	

<p class="text-center m-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_deck_index');?>">zurück zur Liste</a> &bull;
	<input class="btn btn-primary" type="submit" name="updateDeckdata" value="speichern">
	<input type="hidden" name="id" value="<?php echo $deckdata->getId(); ?>">
</p>
</form>