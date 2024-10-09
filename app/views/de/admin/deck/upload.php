<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_index');?>">Karten</a></li>
    <li class="breadcrumb-item active" aria-current="page">hinzufügen</li>
  </ol>
</nav>

<h1>Deck hinzufügen</h1>

<form id="add-deck-form" enctype="multipart/form-data" method="POST" action="" name="upload">

	<div class="card my-4">
		<div class="card-header">
			Stammdaten
		</div>
		<div class="card-body">
		
    		<div class="row">
        		<div class="form-group col-12 col-lg-6">
            		<label for="name">Name <br></label>
            		<input class="form-control" name="name" id="name" type="text" maxlength="255" pattern="[A-Za-z0-9äÄüÜöÖß _\-]+" required>
            		<small class="text-muted">Erlaubte Zeichen:<br><i>Buchstaben, Zahlen, Leerzeichen und "_","-"</i></small>
            	</div>
        		<div class="form-group col-12 col-lg-6">
            		<label for="deckname">Kürzel <br></label>
            		<input class="form-control" name="deckname" id="deckname" type="text" maxlength="50" pattern="[a-z0-9_\-]+" required>
            		<small class="text-muted">Erlaubte Zeichen:<br><i>Kleinbuchstaben (<b>ohne</b> Umlaute), Zahlen und "_","-"</i></small>
            	</div>
        	</div>
        	
    		<div class="row">
        		<div class="form-group col-12 col-lg-6">
            		<label for="category">Kategorie <br></label>
            		<select class="form-control" id="category" name="subcategory" required>
            			<?php foreach($categories as $category){ ?>
            			<optgroup label="<?php echo $category->getName(); ?>">
            				<?php foreach($category->getSubcategories() as $subcategory){ ?>
            				<option value="<?php echo $subcategory->getId(); ?>"><?php echo $subcategory->getName(); ?></option>
            				<?php } ?>
            			</optgroup>
            			<?php } ?>
            		</select>
            	</div>
            	<div class="form-group col-12 col-lg-6">
            		<label for="type">Typ</label>
            		<select class="form-control" id="type" name="type" required>
            			<?php foreach($deck_types as $type){ ?>
            				<option value="<?php echo $type->getId(); ?>" data-size="<?php echo $type->getSize(); ?>">
            					<?php echo $type->getName(); ?> (<?php echo $type->getSize(); ?> Karten)
            				</option>
            			<?php } ?>
            		</select>
            	</div>
            </div>
            
            <div class="row">
            	<div class="form-group col">
                	<label for="description">Infotext <br></label>
                	<textarea class="form-control" name="description" 
                		placeholder="Infos zum Deck, wie z.B. Quellenangaben... (optional)"></textarea>
                	<small class="text-muted">Du kannst 
                		<a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
                		zur Formatierung nutzen, sowie HTML!</small>
                </div>
            </div>
            
    	</div>
	</div>

	<div class="card my-4">
		<div class="card-header">
			Karten
		</div>
		<div class="card-body ">
			
			<div class="text-right">
				<label for="show-file-upload">Dateien hochladen <input type="checkbox" id="show-file-upload" checked></label>
			</div>

			<div id="file-upload-information" class="d-none">
				<?php echo $this->renderMessage('info','Please upload the files manually!'); ?>
			</div>

			<div id="file-uploads">
				<div class="row">
					<?php for($i = 1; $i <= $deck_types[0]->getSize(); $i++){ ?>
					<div class="col-12 col-lg-6 form-group">
						<label for="file<?php echo $i; ?>">Karte <?php echo $i; ?></label>
						<input class="form-control form-control-sm" type="file" id="file<?php echo $i; ?>" name="card_<?php echo $i; ?>">
					</div>
					<?php } ?>
				</div>
				<div class="form-group">
					<label for="file_master">Master Karte</label>
					<input class="form-control form-control-sm" type="file" id="file_master" name="_master">
				</div>
			</div>
    	</div>
	</div>
    	
    <p class="my-4 text-center"><input class="btn btn-primary" type="submit" name="upload" value="hochladen"></p>
    	
	<div class="my-4">
    	<h6>Hinweise:</h6>
    	<ul>
    		<li>Kürzel ist der Name des Decks der in der Regel auf den Karten steht.</li>
    		<li>Der Name enthält den vollen Namen der auf der Deck Unterseite angezeigt wird.</li> 
    		<li>Karten werden in der Reihenfolge ausgewählt wie sie später angezeigt werden sollen. 
    			Die Vergabe der Dateinamen erfolgt automatisch!</li>
    	</ul>
	</div>

</form>

<input type="hidden" id="name-string" value="Karte">
<script>document.addEventListener("DOMContentLoaded", () => { new DeckUpload() });</script>