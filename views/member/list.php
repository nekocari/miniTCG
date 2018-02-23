<h1>Mitgliederliste</h1>


<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Name</th>
    		<th>Level</th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($members as $member){ ?>
    	<tr>
    		<td><?php echo $member->id; ?></td>
    		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->name; ?></a></td>
    		<td><?php echo $member->level; ?></td>
    	</tr>
<?php } ?>
	</tbody>
</table>