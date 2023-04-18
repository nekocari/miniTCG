<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Mitglieder</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo $currency_name; ?> geben</li>
  </ol>
</nav>

<h1><?php echo $currency_name; ?> geben</h1>

<h4>Benutzer: <b><?php echo $member->getName(); ?></b></h4>

<form method="POST" action="">
    <div class="mx-2 mb-2">Wie viel <?php echo $currency_name; ?> gutschreiben?</div>
    <div class="mx-2 mb-2"><input type="number" name="addMoney" class="form-control" required></div>
    <div class="mx-2 mb-2"><textarea name="text" class="form-control" placeholder="Grund der Gutschrift?" required></textarea></div>
    <div class="mx-2 mb-2"><button class="btn btn-primary" name="add" value="1">Gutschrift durchführen</button></div>
</form>