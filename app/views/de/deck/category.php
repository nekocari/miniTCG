<h1><?php echo $category->getName(); ?></h1>

<?php if(count($category->getSubcategories()) > 0){ ?>
<div class="table-responsive">
    <table class="table">
	<?php foreach($category->getSubcategories() as $subcategory){ ?>
		<thead class="thead-light">
        	<tr>
        		<th colspan="3"><?php echo $subcategory->getName(); ?></th>
        	</tr>
    	</thead>
		<?php if(count($subcategory->getDecks()) > 0){ ?>
        	<?php foreach($subcategory->getDecks() as $deck){ ?>
        	<tr>
        		<td><?php echo $deck->getId(); ?></td>
        		<td>
        			<a class="deckname" href="<?php echo $deck->getDeckpageUrl(); ?>"><?php echo $deck->getDeckname(); ?></a><br>
        			<small><?php echo $deck->getName(); ?></small>
        		</td>
        		<td>
        			<?php if($this->login->isloggedIn()){ echo $deck->getDate($this->login->getUser()->getTimezone()); } else { echo $deck->getDate(); } ?><br>
        			<small>von <?php echo $deck->getCreatorName(); ?></small>
        		</td>
        	</tr>
        	<?php } ?>
    	<?php }else{ echo "<tr><td colspan='3'>"; $this->renderMessage('info','Keine Decks'); echo "</tr></td>"; }
        } ?>
	</table>
</div>
<?php }else{ $this->renderMessage('info','Noch keine Unterkategorien angelegt.'); } ?>