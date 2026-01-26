<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard')?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
  </ol>
</nav>


<h1>Settings</h1>
<form method="POST" action="">
    	<?php foreach($settings as $setting){ 
    		
    		if(!isset($group) OR $group != explode('_',$setting->getName())[0]){
    			if(isset($group)){ // close previous group element if exits ?>
    				</ul>
				<?php }
    			$group = explode('_',$setting->getName())[0]; // use first part of key as group heading ?>
    			<h2 class="mt-4"><?php echo $group; ?> </h2>
    			<ul class="list-group"> <!-- start new list group -->
    		<?php }	?>
    	
	    	<li class="list-group-item d-flex justify-content-between align-items-center">
	    		<div>
	    			<?php echo $setting->getName(); ?><br>
	    			<small class="text-muted"><?php echo $setting->getDescription('de'); ?></small>
	    		</div>
	    		<div>
	    			<?php switch($setting->getMeta('type')) { 
	    				case 'bool': ?>
		    				<input type="radio" name="settings[<?php echo $setting->getName(); ?>]" value="1" <?php if($setting->getValue() == 1){ echo 'checked'; } ?>> Yes
		    				<input type="radio" name="settings[<?php echo $setting->getName(); ?>]" value="0" <?php if($setting->getValue() == 0){ echo 'checked'; } ?>> No
	    				<?php 
	    				break;
	    				case 'select': ?>
		    				<select name="settings[<?php echo $setting->getName(); ?>]" class="form-select" required>
		    					<?php foreach(explode(',',$setting->getMeta('allowed_values')) as $allowed_value){ ?>
		    						<option <?php if($setting->getValue() == $allowed_value){ echo 'selected'; } ?>><?php echo $allowed_value; ?></option>
		    					<?php } ?>
		    				</select>
	    				<?php 
	    				break;
	    				default: ?>
	    					<input type="<?php echo $setting->getMeta('type') ?>" class="form-control" name="settings[<?php echo $setting->getName(); ?>]" value="<?php echo $setting->getValue(); ?>" required>
	    				<?php break;
	    			} ?>
	    		</div>
	    	</li>
    	<?php } ?>
    </ul>
    
    <p class="text-right my-4"><input type="submit" class="btn btn-primary" name="updateSettings" value="save"></p>
</form>