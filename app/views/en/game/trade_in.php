<h1>Trade In</h1>
<h2>Let's play!</h2>


<p class="text-center">Trade in your duplicate card for a random card!</p>
<form method="post" class="form text-center">

<?php if(count($cards)){ ?>
	<p>
        <select name="card" class="form-control d-inline w-auto">
        <?php foreach($cards as $option){ ?>
        	<option value="<?php echo $option['card']->getId(); ?>"><?php echo $option['card']->getName().' ('.$option['possessionCounter'].')'; ?></option>
        <?php } ?>
        </select>
		<button class="btn btn-primary" type="submit" name="play" value="1">trade in</button>
	</p> 	
<?php }else{ 
	$this->renderMessage('info','You do not own any duplicate cards.'); ?>
	<p class="text-center">
    	<a class="btn btn-dark" href="javascript:history.go(-1)">go back</a>
    </p>
<?php } ?>
</form>