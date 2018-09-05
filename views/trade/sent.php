<h1>Tauschanfragen</h1>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link" href="<?php echo Routes::getUri('trades_recieved'); ?>">Erhalten</a></li>
	<li class="nav-item"><a class="nav-link active">Gesendet</a></li>
</ul>

<?php foreach($trades as $trade){ ?>
<div class="card my-4">
	<div class="card-header text-muted text-right font-italic">
		<small>an <a class="font-weight-bold" href="<?php echo $trade->getRecipient()->getProfilLink(); ?>"><?php echo $trade->getRecipient()->getName(); ?></a> 
			am <?php echo $trade->getDate(); ?></small>
	</div>
	<div class="card-body text-center">
		<div>
			<?php echo $trade->getOfferedCard()->getImageHTML(); ?>
			<i class="fas fa-sync-alt h1"></i>
			<?php echo $trade->getRequestedCard()->getImageHTML(); ?>
		</div>
		<div>Deine <span class="text-uppercase"><?php echo $trade->getOfferedCard()->getName(); ?></span> gegen 
			<span class="text-uppercase"><?php echo $trade->getRequestedCard()->getName(); ?></span> von <?php echo $trade->getRecipient()->getName(); ?></div>
		<div class="text-muted font-italic">Nachricht: "<?php echo $trade->getText(); ?>"</div>
	</div>
	<div class="card-footer text-center">
		<form class="m-0" method="post" action="">
			<button class="btn btn-sm btn-danger" name="delete"><i class="fas fa-times"></i> löschen</button>
			<input type="hidden" name="id" value="<?php echo $trade->getId(); ?>">
		</form>
	</div>
</div>
<?php } ?>

<?php if(count($trades) == 0){ Layout::sysMessage('Du hast keine Tauschanfragen gesendet.'); } ?>