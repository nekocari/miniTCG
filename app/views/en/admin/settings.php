<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard')?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
  </ol>
</nav>

<form method="POST" action="">
    <ul class="list-group">
    	<?php foreach($settings as $setting){ ?>
    	<li class="list-group-item d-flex justify-content-between align-items-center">
    		<div>
    			<?php echo $setting->getName(); ?><br>
    			<small class="text-muted"><?php echo $setting->getDescription('en'); ?></small>
    		</div>
    		<div>
    			<input class="form-control" name="settings[<?php echo $setting->getName(); ?>]" value="<?php echo $setting->getValue(); ?>" required>
    		</div>
    	</li>
    	<?php } ?>
    </ul>
    
    <p class="text-right my-4"><input type="submit" class="btn btn-primary" name="updateSettings" value="save"></p>
</form>