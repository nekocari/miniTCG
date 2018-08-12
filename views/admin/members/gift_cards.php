<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_member_list');?>">Mitglieder</a></li>
    <li class="breadcrumb-item active" aria-current="page">Karten geben</li>
  </ol>
</nav>

<h1>Karten geben</h1>

<h4>Benutzer: <b><?php echo $member->getName(); ?></b></h4>

<form method="POST" action="" class="form-inline">
    <div class="mx-2 mb-2">Wie viele Karten gutschreiben?</div>
    <div class="mx-2 mb-2"><input type="number" name="addCards" class="form-control"></div>
    <div class="mx-2 mb-2"><button class="btn btn-primary" name="add" value="1">Gutschrift durchführen</button></div>
</form>