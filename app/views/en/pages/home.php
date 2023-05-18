<div class="bg-primary-subtle rounded-2 p-5 mb-5">
	<?php if(Setting::getByName('app_name')->getValue() == 'miniTCG'){ ?>
		<h1 class="ff-brand">mini<span class="text-primary">TCG</span></h1>
		<p>A small Trading Card Game Applikation in PHP</p>
	<?php }else{ ?>
		<h1 class="ff-brand"><?php echo Setting::getByName('app_name')->getValue(); ?></h1>
	<?php } ?>
</div>



<?php News::display(3,'en',$this->login); ?>