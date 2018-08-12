<h1>Mitgliedsbereich</h1>

<hr>

<div class="row">
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-secondary w-100" href="<?php echo ROUTES::getUri('member_cardmanager');?>">
			<i class="fas fa-folder-open h1"></i><br>
			Karten Manager
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-secondary w-100" href="<?php echo ROUTES::getUri('member_trademanager');?>">
			<i class="fas fa-exchange-alt h1"></i><br>
			Tauschanfragen
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-secondary w-100" href="<?php echo ROUTES::getUri('member_mastercards');?>">
			<i class="fas fa-trophy h1"></i><br>
			Master Karten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-secondary w-100" href="<?php echo ROUTES::getUri('member_cardupdate');?>">
			<i class="fas fa-star h1"></i><br>
			Update Karten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-secondary w-100" href="<?php echo ROUTES::getUri('member_edit_profil');?>">
			<i class="fas fa-user h1"></i><br>
			Profil Daten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-secondary w-100" href="<?php echo ROUTES::getUri('tradelog_member');?>">
			<i class="fas fa-book h1"></i><br>
			Tradelog
		</a>
	</div>
</div>

<hr>

<div class="row">
	<div class="col text-center m-4">
    	<a class="btn btn-dark" href="<?php echo ROUTES::getUri('signout');?>">AUSLOGGEN</a>
    </div>
</div>