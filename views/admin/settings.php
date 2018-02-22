<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="admin/">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Einstellungen</li>
  </ol>
</nav>

<form method="POST" action="">
    <ul class="list-group">
    	<?php foreach($settings as $setting){ ?>
    	<li class="list-group-item d-flex justify-content-between align-items-center">
    		<div>
    			<?php echo $setting->getName(); ?><br>
    			<small class="text-muted"><?php echo $setting->getDescription(); ?></small>
    		</div>
    		<div>
    			<input class="form-control" name="settings[<?php echo $setting->getName(); ?>]" value="<?php echo $setting->getValue(); ?>">
    		</div>
    	</li>
    	<?php } ?>
    </ul>
    
    <p class="text-right my-4"><input type="submit" class="btn btn-primary" name="updateSettings" value="speichern"></p>
</form>