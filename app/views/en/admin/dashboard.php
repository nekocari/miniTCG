<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Administration</li>
  </ol>
</nav>

<h1>Manage your TCG</h1>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-bullhorn"></i> News</h4>
        		<small>Write and edit news entries</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('news_add');?>">write</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('news_index');?>">view list</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-users"></i> Members</h4>
        		<small>Manage your members</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_member_search');?>">search</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_member_index');?>">view list</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-clone"></i> Cards</h4>
        		<small>Manage your cards</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('deck_upload');?>">add</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_deck_index');?>">view list</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('deck_update');?>">Updates</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-gamepad"></i> Games</h4>
        		<small>Manage your games</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_games_add');?>">add</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_games_index');?>">view list</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-wrench"></i> Structure</h4>
        		<small>Structure and display relevant data</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('category_index');?>">Categories</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('level_index');?>">Level</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_deck_type_index');?>">Deck Types</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_card_status_index');?>">Manager Categories</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-cogs"></i> Application</h4>
        		<small>Basic settings for your TCG</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_pages');?>">Pages</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_settings');?>">Settings</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_routes_index');?>">Routing</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_sql_import');?>">SQL Import</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>

<p class="text-center"><a class="btn btn-dark" href="signout.php">LOGOUT</a></p>