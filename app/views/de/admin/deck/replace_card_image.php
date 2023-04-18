<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_index');?>">Karten</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('deck_edit')."?id=".$deck->getId();?>">bearbeiten</a></li>
    <li class="breadcrumb-item active" aria-current="page">Bilddatei ersetzen</li>
  </ol>
</nav>

<h1><span class="deckname"><?php echo $deck->getDeckname(); ?></span> bearbeiten</h1>

<h2>Bilddatei ersetzen</h2>

<form enctype="multipart/form-data" method="POST" action="" name="replace_card">

<table class="table">
	<tr>
		<td>Welche Nummer ersetzen?</td>
		<td>
        	<select name="number" class="form-control">
        		<?php foreach($card_keys as $i){ ?>
        		<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        		<?php } ?>
        	</select>
        </td>
    </tr>
    <tr>
    	<td>Ersetzen mit:</td>
    	<td><input type="file" name="file" class="form-control"></td>
    </tr>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('deck_edit')."?id=".$deck->getId();?>">zurück zum Deck</a> &bull;
	<input class="btn btn-primary" type="submit" name="replace_card" value="ersetzen">
	<input type="hidden" name="deck_id" value="<?php echo $deck->getId(); ?>">
</p>
</form>