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
    <li class="breadcrumb-item active" aria-current="page">edit HTML Template</li>
  </ol>
</nav>

<h1>HTML Template</h1>

<h2>Deck Typ: <?php echo $deck_type->getName(); ?></h2>

<form method="post" action="">
<table class="table table-striped">
	<tbody>
    	<tr>
    		<td>File path</td>
    		<td><div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><?php echo DeckType::getTemplateBasePath(); ?></div>
    			</div>
    			<input class="form-control" type="text" name="" value="<?php echo $deck_type->getTemplatePath(); ?>" disabled>
    		</div></td>
    	</tr>
    	<tr>
    		<td colspan="2">
    		<textarea class="form-control" name="template_content" style="max-height: 25vh; font-family: monospace;" rows="10" ><?php echo $deck_type->getTemplateContent(); ?></textarea>
    		</td>
    	</tr>
	</tbody>
</table>

<p class="text-center mx-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_deck_type_edit').'?id='.$deck_type->getId();?>">go to Deck Type</a> &bull;
	<input class="btn btn-primary" type="submit" name="saveTemplate" value="save">
	<input type="hidden" name="id" value="<?php echo $deck_type->getId(); ?>">
</p>
</form>