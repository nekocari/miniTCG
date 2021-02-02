<h1>Mitgliederliste</h1>

<table class="table">
<?php foreach($level as $lv){ 
    if(isset($members[$lv->getId()])){
?>
	<tr>
		<th colspan="3">
			<div class="row justify-content-between">
			<span>Level <?php echo $lv->getLevel(); ?> <?php echo $lv->getName(); ?></span>
			<span class="badge badge-dark"><?php echo count($members[$lv->getId()]); ?> Mitglieder</span>
			</div>
		</th>
	</tr>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Level</th>
	</tr>
	<?php foreach($members[$lv->getId()] as $member){ ?>
    	<tr>
    		<td><?php echo $member->getId(); ?></td>
    		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->getName(); ?></a></td>
    		<td><?php echo $lv->getName(); ?></td>
    	</tr>
	<?php } ?>
<?php } } ?>
</table>