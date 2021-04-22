<h1>Tradelog</h1>

<?php echo $pagination; ?>


<?php if(count($entries) == 0){ Layout::sysMessage('Es gibt keine Einträge.'); } ?>

<table class="table table-sm table-striped">
	<thead>
    	<tr>
    		<th>Datum</th>
    		<th>Log Text</th>
    	</tr>
	</thead>
	<tbody>
    	<?php foreach($entries as $entry){ ?>
    	<tr>
    		<td><?php echo $entry->getDate(); ?></td>
    		<td class="font-italic"><?php echo $entry->getTextStripeId(); ?></td>
    	</tr>
    	<?php } ?>
	</tbody>
</table>

<?php echo $pagination; ?>