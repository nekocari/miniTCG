<h1>Member Area</h1>

<hr>

<div class="row">
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_cardmanager');?>">
			<i class="fas fa-folder-open h1"></i><br>
			Card Manager
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('trades_recieved');?>">
			<i class="fas fa-exchange-alt h1"></i><br>
			Trade Offers
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('messages_recieved');?>">
			<i class="fas fa-envelope-open h1"></i><br>
			Messages
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_mastercards');?>">
			<i class="fas fa-trophy h1"></i><br>
			Master Cards
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('member_cardupdate');?>">
			<i class="fas fa-star h1"></i><br>
			Update Cards
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('game');?>">
			<i class="fas fa-gamepad h1"></i><br>
			Games
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('shop');?>">
			<i class="fas fa-shopping-cart h1"></i><br>
			Card Shop
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('card_search');?>">
			<i class="fas fa-search h1"></i><br>
			Card Search
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
			Profil Data
		</a>
	</div>
	<div class="col-6 col-md-4 text-center p-4">
		<a class="btn btn-outline-dark w-100" href="<?php echo Routes::getUri('delete_account');?>">
			<i class="fas fa-trash-alt h1"></i><br>
			Delete Account
		</a>
	</div>
</div>

<hr>

<div class="row">
	<div class="col text-center m-4">
    	<a class="btn btn-dark" href="<?php echo Routes::getUri('signout');?>">LOGOUT</a>
    </div>
</div>