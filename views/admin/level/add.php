<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="admin/level.php">Level</a></li>
    <li class="breadcrumb-item active" aria-current="page">anlegen</li>
  </ol>
</nav>

<h1>Level anlegen</h1>

<form method="post" action="">
    <table class="table table-striped">
    	<tr>
    		<td>Level Stufe:</td>
    		<td><input class="form-control" type="number" name="level"></td>
    	</tr>
    	<tr>
    		<td>Level Name:</td>
    		<td><input class="form-control" type="text" name="name"></td>
    	</tr>
    	<tr>
    		<td>Benötigte Karten Anzahl:</td>
    		<td><input class="form-control" type="number" name="cards"></td>
    	</tr>
    </table>

	<p class="text-center">
		<a class="btn btn-dark" href="admin/level.php">zurück zur Liste</a> 
		&bull; <input class="btn btn-primary" type="submit" name="addLevel" value="anlegen">
	</p>
</form>