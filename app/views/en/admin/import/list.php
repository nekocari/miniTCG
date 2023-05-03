<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">SQL Import</li>
  </ol>
</nav>

<h1>Import Database Updates</h1>

<div class="alert alert-primary">This is where you can import sql files from the folder "import",
to update you database after the app recieved an update.</div>


<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>Filename</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($files as $file){ ?>
    	<tr style="white-space:nowrap">
    		<td><?php echo $file; ?></td>
    		<td>
    			<form class="text-right" method="POST" action="">
        			<button class="btn btn-sm btn-primary" name="import" value="<?php echo $file; ?>">
        				<i class="fas fa-database"></i> import</button>
        			<button class="btn btn-sm btn-danger" name="delete" value="<?php echo $file; ?>" onclick="return confirm('Delete file from server?');">
        				<i class="fas fa-times"></i> delte</button>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>