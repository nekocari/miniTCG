<h1>Mitgliedsbereich</h1>

<hr>

<div class="row">
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_cardmanager');?>">
			<i class="fas fa-folder-open h1"></i><br>
			Karten Manager
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('trades_recieved');?>">
			<i class="fas fa-exchange-alt h1"></i><br>
			Tauschanfragen
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('messages_recieved');?>">
			<i class="fas fa-envelope-open h1"></i><br>
			Nachrichten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_mastercards');?>">
			<i class="fas fa-trophy h1"></i><br>
			Master Karten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_cardupdate');?>">
			<i class="fas fa-star h1"></i><br>
			Update Karten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_wishlist');?>">
			<i class="fas fa-heart h1"></i><br>
			Wunschliste
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('game');?>">
			<i class="fas fa-gamepad h1"></i><br>
			Spiele
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('shop');?>">
			<i class="fas fa-shopping-cart h1"></i><br>
			Karten Shop
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('card_search');?>">
			<i class="fas fa-search h1"></i><br>
			Karte Suchen
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('tradelog_member');?>">
			<i class="fas fa-book h1"></i><br>
			Tradelog
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('edit_userdata');?>">
			<i class="fas fa-user h1"></i><br>
			Profil Daten
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('delete_account');?>">
			<i class="fas fa-trash-alt h1"></i><br>
			Benutzerkonto löschen
		</a>
	</div>
</div>

<hr>

<div class="row">
	<div class="col text-center m-4">
    	<a class="btn btn-dark" href="<?php echo Routes::getUri('signout');?>">AUSLOGGEN</a>
    </div>
</div>