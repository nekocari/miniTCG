<h1>Games</h1>

<table class="table table-striped">
	<thead>
    	<tr>
    		<th class="text-left">Game Name</th>
    		<th class="text-left">playable?</th>
    	</tr>
	</thead>
	<tbody>
    	<?php foreach($games as $game){ ?>
    	<tr>
    		<td class="text-left"><?php echo $game['name'] ?></td>
    		<td class="text-left"><?php echo $game['link'] ?></td>
    	</tr>
    	<?php } ?>
    </tbody>
</table>