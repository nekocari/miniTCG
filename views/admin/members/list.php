<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
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
    		<td><?php echo $member->id; ?></td>
    		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->name; ?></a></td>
    		<td><?php echo $member->mail; ?></td>
    		<td><form method="post" action="admin/members/edit.php?id=<?php echo $member->id; ?>" class="text-right">
    				<button class="btn btn-link"><i class="fas fa-pencil-alt"></i> bearbeiten</button>
    			</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>