<div class="jumbotron">
	<h1 class="display-4">
		<?php if(Setting::getByName('app_name')->getValue() == 'miniTCG'){ ?>
				 mini<span class="text-primary">TCG</span>
		<?php }else{ echo Setting::getByName('app_name')->getValue(); } ?>
	</h1>
	<p>Eine kleine Trading Card Game Applikation in PHP</p>
</div>


<?php News::display(3,'de',$this->login); ?>