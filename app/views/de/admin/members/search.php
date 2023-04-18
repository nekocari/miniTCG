<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mitglieder</li>
  </ol>
</nav>

<h1>Mitgliedersuche</h1>


<form method="post" action="">
	<p><input class="form-control" type="text" name="search" placeholder="Wen suchst du?"></p>
	<p><button name="submit" class="btn btn-primary">suchen</button></p>
</form>