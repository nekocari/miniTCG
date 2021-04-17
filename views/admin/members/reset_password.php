<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_member_index');?>">Mitglieder</a></li>
    <li class="breadcrumb-item active" aria-current="page">Passwort zurücksetzen</li>
  </ol>
</nav>

<h1>Passwort zurücksetzen</h1>

<h4>Benutzer: <b><?php echo $member->getName(); ?></b></h4>


<div class="alert alert-warning">
	<p>Hier kannst du das Passwort des Benutzers zurücksetzen. Dem Benutzer wird das Passwort dabei 
	an die hinterlegte E-Mailadresse gesendet.</p>
	<p><b>Diese Aktion kann nicht rückgängig gemacht werden!</b></p> 
</div>

<form name="resetPassword" method="post" action="" onsubmit="return confirm('Bist du sicher?')">
	<div class="text-center">
		<button class="btn btn-danger" type="submit" name="reset">Passwort für <?php echo $member->getName(); ?> zurücksetzen</button>
	</div>
</form>