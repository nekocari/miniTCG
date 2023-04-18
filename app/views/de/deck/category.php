<h1><?php echo $category->getName(); ?></h1>

<div class="table-responsive">
    <table class="table">
    	<?php foreach($category->getSubcategories() as $subcategory){ 
    	    if(count($subcategory->getDecks()) > 0){ ?>
		<thead class="thead-light">
        	<tr>
        		<th colspan="3"><?php echo $subcategory->getName(); ?></th>
        	</tr>
    	</thead>
        	<?php foreach($subcategory->getDecks() as $deck){ ?>
        	<tr>
        		<td><?php echo $deck->getId(); ?></td>
        		<td><a class="deckname" href="<?php echo $deck->getDeckpageUrl(); ?>"><?php echo $deck->getDeckname(); ?></a><br>
        			<small><?php echo $deck->getName(); ?></small></td>
        		<td><?php echo $deck->getDate($this->login->getUser()->getTimezone()); ?><br>
        			<small>von <?php echo $deck->getCreatorName(); ?></small></td>
        	</tr>
        	<?php } ?>
    	<?php }
        } ?>
	</table>
</div>