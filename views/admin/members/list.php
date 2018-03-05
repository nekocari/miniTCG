<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mitglieder</li>
  </ol>
</nav>

<h1>Mitgliederliste</h1>


<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Name</th>
    		<th>Mail</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($members as $member){ ?>
    	<tr>
    		<td><?php echo $member->getId(); ?></td>
    		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->getName(); ?></a></td>
    		<td><?php echo $member->getMail(); ?></td>
    		<td class="text-right"><a href="<?php echo ROUTES::getUri('admin_member_edit');?>?id=<?php echo $member->getId(); ?>" class="btn btn-link">
    			<i class="fas fa-pencil-alt"></i> bearbeiten</a>    			
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>

<?php echo $pagination; ?>