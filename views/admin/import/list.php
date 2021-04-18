<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">SQL Import</li>
  </ol>
</nav>

<h1>Datenbank Updates importieren</h1>

<div class="alert alert-primary">Hier kannst du die SQL Dateien aus dem Ordner "import" einlesen, 
um z.B. nach einem Update der App deine Datenbank zu aktualisieren.</div>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>Dateiname</th>
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
        				<i class="fas fa-database"></i> importieren</button>
        			<button class="btn btn-sm btn-danger" name="delete" value="<?php echo $file; ?>" onclick="return confirm('Datei vom Server löschen?');">
        				<i class="fas fa-times"></i> löschen</button>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>