<h1>Tauschanfragen</h1>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link active">Erhalten</a></li>
	<li class="nav-item"><a class="nav-link" href="<?php echo Routes::getUri('trades_sent'); ?>">Gesendet</a></li>
</ul>

<?php foreach($trades as $trade){ ?>
<div class="card my-4">
	<div class="card-header text-muted text-right font-italic">
		<small>von <a class="font-weight-bold" href="<?php echo $trade->getOfferer()->getProfilLink(); ?>"><?php echo $trade->getOfferer()->getName(); ?></a> 
			am <?php echo $trade->getDate(); ?></small>
	</div>
	<div class="card-body text-center">
		<div>
			<?php echo $trade->getRequestedCard()->getImageHTML(); ?>
			<i class="fas fa-sync-alt h1"></i>
			<?php echo $trade->getOfferedCard()->getImageHTML(); ?>
		</div>
		<div>Deine <span class="text-uppercase"><?php echo $trade->getRequestedCard()->getName(); ?></span> gegen 
			<span class="text-uppercase"><?php echo $trade->getOfferedCard()->getName(); ?></span> von <?php echo $trade->getOfferer()->getName(); ?></div>
		<div class="text-muted font-italic">Nachricht: "<?php echo $trade->getText(); ?>"</div>
	</div>
	<div class="card-footer text-center">
		<form class="m-0 p-0" method="POST" action="">
			<input type="text" class="form-control my-1" maxlength="250" name="text" placeholder="Nachricht (optional)">
    		<button class="btn btn-sm btn-primary" name="accept"><i class="fas fa-check"></i> Ja</button>
    		&bull; 
    		<button class="btn btn-sm btn-danger" name="decline"><i class="fas fa-times"></i> Nein</button>
    		<input type="hidden" name="id" value="<?php echo $trade->getId(); ?>">
    	</form>
	</div>
</div>

<?php } ?>