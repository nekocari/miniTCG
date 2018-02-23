<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Karten hochladen</li>
  </ol>
</nav>

<h1>Karten hochladen</h1>

<form enctype="multipart/form-data" method="POST" action="" name="upload">

	<div class="card my-4">
		<div class="card-header">
			Stammdaten
		</div>
		<div class="card-body form-row">
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
	</div>

	<div class="card my-4">
		<div class="card-header">
			Karten
		</div>
		<div class="card-body form-row">
    		<?php for($i = 1; $i <= $settings['decksize']; $i++){ ?>
    		<div class="col-12 col-lg-6 form-group">
    			<label for="file<?php echo $i; ?>">Karte <?php echo $i; ?></label>
    			<input class="form-control form-control-sm" type="file" id="file<?php echo $i; ?>" name="card_<?php echo $i; ?>">
    		</div>
    		<?php } ?>
    		<div class="col-12 col-lg-6 form-group">
    			<label for="file_master">Master Karte</label>
    			<input class="form-control form-control-sm" type="file" id="file_master" name="_master">
    		</div>
    	</div>
	</div>
    	
    <p class="my-4 text-center"><input class="btn btn-primary" type="submit" name="upload" value="hochladen"></p>
    	
	<div class="my-4">
    	<h6>Hinweise:</h6>
    	<small>
    	<ul>
    		<li>Kürzel ist der Name des Decks der in der Regel auf den Karten steht.</li>
    		<li>Der Name enthält den vollen Namen der auf der Deck Unterseite angezeigt wird.</li> 
    		<li>Karten werden in der Reihenfolge ausgewählt wie sie später angezeigt werden sollen. 
    			Die Vergabe der Dateinamen erfolgt automatisch!</li>
    	</ul>
    	</small>
	</div>

</form>