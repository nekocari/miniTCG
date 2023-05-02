<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Verwaltung</li>
  </ol>
</nav>

<h1>Verwalte das TCG</h1>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-bullhorn"></i> News</h4>
        		<small>Neuigkeiten für die Startseite</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('news_add');?>">schreiben</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('news_index');?>">Liste anzeigen</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-users"></i> Mitglieder</h4>
        		<small>Verwaltung der Mitglieder</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_member_search');?>">suchen</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_member_index');?>">Liste anzeigen</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-clone"></i> Karten</h4>
        		<small>Verwalte die Karten des TCG</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('deck_upload');?>">hochladen</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_deck_index');?>">Liste anzeigen</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('deck_update');?>">Updates</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-gamepad"></i> Spiele</h4>
        		<small>Verwaltung der Spiele</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_games_add');?>">anlegen</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_games_index');?>">Liste anzeigen</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-wrench"></i> Struktur</h4>
        		<small>Aufbau und Anzeigerelevante Daten</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('category_index');?>">Kategorien</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('level_index');?>">Level</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_deck_type_index');?>">Deck Arten</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_card_status_index');?>">Manager Kategorien</a>
        		</nav>
        	</div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-4">
        	<div class="card-body">
        		<h4><i class="fas fa-cogs"></i> Applikation</h4>
        		<small>Grundeinstellungen des TCG</small>
        		<nav class="nav justify-content-end">
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_settings');?>">Einstellungen</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_routes_index');?>">Routing</a>
        			<a class="nav-link" href="<?php echo Routes::getUri('admin_sql_import');?>">SQL Import</a>
        		</nav>
        	</div>
        </div>
    </div>
</div>

<p class="text-center"><a class="btn btn-dark" href="signout.php">AUSLOGGEN</a></p>