<h1>Mitgliederliste</h1>


<table class="table">
	<?php foreach($members as $lv => $members){ ?>
	<tr>
		<th colspan="3">
			<h4>Level <?php echo $lv.": ".$level[$lv]->getName(); ?> <span class="badge badge-dark"><?php echo count($members); ?></span></h4>
		</th>
	</tr>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Level</th>
	</tr>
	<?php foreach($members as $member){ ?>
	<tr>
		<td><?php echo $member->getId(); ?></td>
		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->getName(); ?></a></td>
		<td><?php echo $member->getLevel(); ?></td>
	</tr>
	<?php } ?>
	<?php } ?>
</table>