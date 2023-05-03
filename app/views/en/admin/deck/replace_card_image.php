<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_index');?>">Cards</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('deck_edit')."?id=".$deck->getId();?>">edit</a></li>
    <li class="breadcrumb-item active" aria-current="page">replace Image</li>
  </ol>
</nav>

<h1>Edit <span class="deckname"><?php echo $deck->getDeckname(); ?></span></h1>

<h2>Replace Image</h2>

<form enctype="multipart/form-data" method="POST" action="" name="replace_card">

<table class="table">
	<tr>
		<td>Which number to replace?</td>
		<td>
        	<select name="number" class="form-control">
        		<?php foreach($card_keys as $i){ ?>
        		<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        		<?php } ?>
        	</select>
        </td>
    </tr>
    <tr>
    	<td>Replace with:</td>
    	<td><input type="file" name="file" class="form-control"></td>
    </tr>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('deck_edit')."?id=".$deck->getId();?>">go to Deck</a> &bull;
	<input class="btn btn-primary" type="submit" name="replace_card" value="replace">
	<input type="hidden" name="deck_id" value="<?php echo $deck->getId(); ?>">
</p>
</form>