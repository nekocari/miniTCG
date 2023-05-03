<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Members</a></li>
    <li class="breadcrumb-item active" aria-current="page">search</li>
  </ol>
</nav>

<h1>Member Search</h1>


<form method="post" action="">
	<p><input class="form-control" type="text" name="search" placeholder="Who are you looking for?"></p>
	<p><button name="submit" class="btn btn-primary">search</button></p>
</form>