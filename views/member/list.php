<h1>Mitgliederliste</h1>


<table style="width: 100%;" border="1">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<td>Level</td>
	</tr>
<?php foreach($members as $member){ ?>
	<tr>
		<td><?php echo $member->id; ?></td>
		<td><?php echo $member->name; ?></td>
		<td><?php echo $member->level; ?></td>
	</tr>
<?php } ?>
</table>