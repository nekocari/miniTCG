<h1>Tauschanfragen</h1>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link active">Erhalten</a></li>
	<li class="nav-item"><a class="nav-link" href="<?php echo RoutesDb::getUri('trades_sent'); ?>">Gesendet</a></li>
</ul>

<?php foreach($trades as $trade){ ?>
<div class="card my-4">
	<div class="card-header text-muted text-right font-italic">
		<small>von <a class="font-weight-bold" href="<?php echo $trade->getOfferer()->getProfilLink(); ?>"><?php echo $trade->getOfferer()->getName(); ?></a> 
			am <?php echo $trade->getDate(); ?></small>
	</div>
	<div class="card-body text-center">
		<div>
			<div class="text-center d-inline-block">
				<div class="d-inline-block card-member-profil">
    				<?php echo $trade->getRequestedCard()->getImageHTML(); ?>
                </div>
            </div>
			<i class="fas fa-sync-alt h1"></i>
			<div class="text-center d-inline-block">
				<div class="d-inline-block card-member-profil <?php 
    				if($trade->getOfferedCard()->missingInKeep()){ echo " card-missing-keep"; } 
    				if($trade->getOfferedCard()->missingInCollect()){ echo " card-missing-collect"; } 
    				if($trade->getOfferedCard()->mastered()){ echo " card-mastered"; } ?>">
    				<?php echo $trade->getOfferedCard()->getImageHTML(); ?>
                </div>
            </div>
		</div>
		<div>
			Deine <span class="text-uppercase"><?php echo $trade->getRequestedCard()->getName(); ?></span> gegen 
			<span class="text-uppercase"><?php echo $trade->getOfferedCard()->getName(); ?></span> von <?php echo $trade->getOfferer()->getName(); ?>
		</div>
			
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

<div class="my-4"><?php if(count($trades) == 0){ $this->renderMessage('info','Du hast keine Tauschanfragen erhalten.'); } ?></div>

<div class="text-center my-2">
	<small>
		<span class="d-inline-block card-member-profil card-missing-keep">fehlend in Keep</span>
		<span class="d-inline-block card-member-profil card-missing-collect">fehlend in Collect</span>
		<span class="d-inline-block card-member-profil card-mastered">Deck gemastert</span>
	</small>
</div>