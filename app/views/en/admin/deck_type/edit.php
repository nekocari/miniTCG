<?php 
/*
 * preset variables:
 * $deck_type -> DeckTypeClass
 * 
 */
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_deck_type_index');?>">Deck Types</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Deck Type</h1>

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
    		<td>Number of Cards</td>
    		<td><input class="form-control" type="number" name="size" value="<?php echo $deck_type->getSize(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Card widht<br><small class="text-muted">in pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="card_image_width" value="<?php echo $deck_type->getCardWidth(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Card height<br><small class="text-muted">in pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="card_image_height" value="<?php echo $deck_type->getCardHeight(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Master Card width<br><small class="text-muted">in pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="master_image_width" value="<?php echo $deck_type->getMasterWidth(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Master Card height<br><small class="text-muted">in pixel</small></td>
    		<td><input class="form-control" type="number" min="1" name="master_image_height" value="<?php echo $deck_type->getMasterHeight(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Filler variant<br></td>
    		<td>
    			<input type="radio" name="filler_type" value="identical" <?php if($deck_type->getFillerType() == 'identical'){ echo 'checked'; } ?> required> identical <br>
    			<small class="text-muted">same image for every Card</small><br>
    			<input type="radio" name="filler_type" value="individual" <?php if($deck_type->getFillerType() == 'individual'){ echo 'checked'; } ?> required> individual <br>
    			<small class="text-muted">different image for every Card number</small>
    		</td>
    	</tr>
    	<tr>
    		<td>Filler path<br>
    		<small class="text-muted">individual = folder path / identical = file path</small></td>
    		<td><input class="form-control" type="text" name="filler_path" value="<?php echo $deck_type->getFillerPath(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Cards per row<br><small class="text-muted">in case no template is used</small></td>
    		<td><input class="form-control" type="number" min="1" name="cards_per_row" value="<?php echo $deck_type->getPerRow(); ?>" required></td>
    	</tr>
    	<tr>
    		<td>Template path<br><small class="text-muted">optional</small></td>
    		<td>
	    		<div class="input-group">
	        		<div class="input-group-prepend d-md-inline">
    				<div class="input-group-text"><?php echo DeckType::getTemplateBasePath(); ?></div>
	    			</div>
    				<input class="form-control" style="min-width: 100px" type="text" name="template_path" placeholder="optional" value="<?php echo $deck_type->getTemplatePath(); ?>">
	    		</div>
	    		<div class="text-right">
	    		<?php if(empty($deck_type->getTemplatePath())){ ?>
	    			<a class="btn btn-sm btn-dark" href="<?php echo Routes::getUri('admin_deck_type_template_edit'); ?>?id=<?php echo $deck_type->getId(); ?>">create</a>
	    		<?php }else{ ?>
	    			<a class="btn btn-sm btn-dark" href="<?php echo Routes::getUri('admin_deck_type_template_edit'); ?>?id=<?php echo $deck_type->getId(); ?>">edit</a>
	    		<?php } ?>
	    		</div>
	    	</td>
    	</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_deck_type_index');?>">go back</a> &bull;
	<input class="btn btn-primary" type="submit" name="updateDeckType" value="save">
	<input type="hidden" name="id" value="<?php echo $deck_type->getId(); ?>">
</p>
</form>