<h1>Mitgliederliste</h1>

<p class="text-center">
<?php foreach($level as $lv){ if(isset($members[$lv->getId()])){ ?>
	<a href="<?php echo Routes::getUri('member_index'); ?>#<?php echo $lv->getLevel(); ?>" class="btn btn-outline-dark"><?php echo $lv->getName(); ?></a>
<?php } } ?>
</p>

<div class="table-responsive">
<table class="table">
<?php foreach($level as $lv){ 
    if(isset($members[$lv->getId()])){
?>
	<thead>
	<tr>
		<th colspan="3" id="<?php echo $lv->getLevel(); ?>">
			<div class="d-flex  m-0 justify-content-between">
			<span>Stufe <?php echo $lv->getLevel(); ?>: <?php echo $lv->getName(); ?></span>
			<span class="badge bg-dark"><?php echo count($members[$lv->getId()]); ?> Mitglieder</span>
			</div>
		</th>
	</tr>
	</thead>
	<tr class="table-light">
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
</div>