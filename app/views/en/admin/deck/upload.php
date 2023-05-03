<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_index');?>">Cards</a></li>
    <li class="breadcrumb-item active" aria-current="page">upload</li>
  </ol>
</nav>

<h1>Upload cards</h1>

<form enctype="multipart/form-data" method="POST" action="" name="upload">

	<div class="card my-4">
		<div class="card-header">
			Basic Data
		</div>
		<div class="card-body">
		
    		<div class="form-row">
        		<div class="form-group col-12 col-lg-6">
            		<label for="name">Full name <br></label>
            		<input class="form-control" name="name" id="name" type="text" maxlength="255" pattern="[A-Za-z0-9äÄüÜöÖß _\-]+" required>
            		<small class="text-muted">Allowed characters:<br><i>Letters, numbers, spaces and "_","-"</i></small>
            	</div>
        		<div class="form-group col-12 col-lg-6">
            		<label for="deckname">Deck name <br></label>
            		<input class="form-control" name="deckname" id="deckname" type="text" maxlength="50" pattern="[a-z0-9_\-]+" required>
            		<small class="text-muted">Allowed characters:<br><i>lowercase letters (<b>without</b> umlauts), numbers and "_","-"</i></small>
            	</div>
        	</div>
        	
    		<div class="form-row">
        		<div class="form-group col-12 col-lg-6">
            		<label for="category">Category <br></label>
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
            		<label for="type">Type</label>
            		<select class="form-control" id="type" name="type" required>
            			<?php foreach($deck_types as $type){ ?>
            				<option value="<?php echo $type->getId(); ?>" data-size="<?php echo $type->getSize(); ?>">
            					<?php echo $type->getName(); ?> (<?php echo $type->getSize(); ?> Karten)
            				</option>
            			<?php } ?>
            		</select>
            	</div>
            </div>
            
            <div class="form-row">
            	<div class="form-group col">
                	<label for="description">Info text <br></label>
                	<textarea class="form-control" name="description" 
                		placeholder="Info about the Deck, e.g. sources... (optional)"></textarea>
                	<small class="text-muted">You can use 
                		<a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a> 
                		to format your text!</small>
                </div>
            </div>
            
    	</div>
	</div>

	<div class="card my-4">
		<div class="card-header">
			Cards
		</div>
		<div class="card-body ">
			<div id="file-uploads" class="form-row">
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
    	
    <p class="my-4 text-center"><input class="btn btn-primary" type="submit" name="upload" value="hochladen"></p>
    	
	<div class="my-4">
    	<h6>Hints:</h6>
    	<ul>
    		<li>Deck name ist the name usually written on the cards and used for the card names.</li>
    		<li>Full name will be displayed on the deck detail page.</li>
    		<li>Cards will be renamed and numbered in the same order they are selected for uploading.</li>
    	</ul>
	</div>

</form>

<script>	
let fileUploads = document.getElementById('file-uploads');
let typeSelect = document.getElementById('type');
typeSelect.addEventListener("change", updateFileUpload);
function updateFileUpload(){ 
	let selected = typeSelect.selectedOptions;
	if(selected.length == 1){
		let upload = '';
    	let deckSize = selected[0].getAttribute('data-size');
    	console.log(deckSize);
	    fileUploads.innerHTML = '';
	    for (i=1;i<=deckSize;i++){ 
	    	upload = '<div class="col-12 col-lg-6 form-group">';
	    	upload+= '<label for="file'+i+'">Karte '+i+'</label>';
			upload+= '<input class="form-control form-control-sm" type="file" id="file'+i+'" name="card_'+i+'">';
	    	upload+= '</div>';
	    	fileUploads.innerHTML+= upload;
	    }
	}else{
		console.log('more than one selected option found for #type'); 
	}
}
</script>