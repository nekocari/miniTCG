<h1>Mitgliederliste</h1>


<table>
<?php foreach($this->members as $member){ ?>
	<tr>
		<td><?php echo $member->id; ?></td>
		<td><?php echo $member->name; ?></td>
		<td><?php echo $member->level; ?></td>
	</tr>
<?php } ?>
</table>