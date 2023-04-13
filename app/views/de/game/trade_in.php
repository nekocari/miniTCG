<h1>Tausch Mich</h1>
<h2>lass uns spielen!</h2>


<p class="text-center">Tausche eine doppelte Karte gegen eine Zufallskarte!</p>
<form method="post" class="form text-center">

<?php if(count($cards)){ ?>
	<p>
        <select name="card" class="form-control d-inline w-auto">
        <?php foreach($cards as $option){ ?>
        	<option value="<?php echo $option['card']->getId(); ?>"><?php echo $option['card']->getName().' ('.$option['possessionCounter'].')'; ?></option>
        <?php } ?>
        </select>
		<button class="btn btn-primary" type="submit" name="play" value="1">eintauschen</button>
	</p> 	
<?php }else{ ?>
	<div class="alert alert-warning">Du hast keine doppelten Karten zum Eintauschen.</div>
	<p class="text-center">
    	<a class="btn btn-dark" href="javascript:history.go(-1)">zurück zur Übersicht</a>
    </p>
<?php } ?>
</form>