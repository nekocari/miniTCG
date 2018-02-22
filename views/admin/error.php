<div class="alert alert-danger" role="alert">
	<h4 class="alert-heading">Fehler</h4>
	<p>Die Aktion konnte nicht ausgeführt werden:</p>
	<ul>
		<?php foreach($errors as $error){ ?>
		<li><?php echo $error; ?></li>
		<?php } ?>
	</ul>
</div>