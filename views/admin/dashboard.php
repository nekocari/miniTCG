<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Verwaltung</li>
  </ol>
</nav>

<h1>Verwalte das TCG</h1>

<div class="row">
    <div class="col-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-bullhorn"></i> News</h4>
        		<small>Neuigkeiten für die Startseite</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo ROUTES::getUri('news_add');?>">schreiben</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('news_index');?>">Liste anzeigen</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-users"></i> Mitglieder</h4>
        		<small>Verwaltung der Mitglieder</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo ROUTES::getUri('admin_member_search');?>">suchen</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('admin_member_index');?>">Liste anzeigen</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-clone"></i> Karten</h4>
        		<small>Verwalte die Karten des TCG</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo ROUTES::getUri('deck_upload');?>">hochladen</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('admin_deck_index');?>">Liste anzeigen</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('deck_update');?>">Updates</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-cogs"></i> Applikation</h4>
        		<small>Einstellungen des TCG</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo ROUTES::getUri('category_index');?>">Kategorien</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('level_index');?>">Level</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('admin_settings');?>">Einstellungen</a>
        			<a class="nav-link" href="<?php echo ROUTES::getUri('admin_sql_import');?>">SQL Import</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>

<p class="text-center"><a class="btn btn-dark" href="signout.php">AUSLOGGEN</a></p>