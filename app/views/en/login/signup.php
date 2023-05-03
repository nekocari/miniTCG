<form class="card mx-auto my-3" style="max-width:400px;" name="signUpForm" method="POST" action="" role="form">
	<div class="card-header">Registration</div>
	<div class="card-body">
        <div class="form-group">
        	<label for="username">Username:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-user"></i></div>
    			</div>
        		<input class="form-control" type="text" name="username" id="username" required pattern="[A-Za-z0-9_äÄöÖüÜß]+">
        	</div>
        	<small class="form-text text-muted">Letters, numbers and "_" are allowed</small>
        </div>
        <div class="form-group">
        	<label for="mail">Email:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-envelope"></i></div>
    			</div>
        		<input class="form-control" type="email" name="mail" id="mail" required>
        	</div>
        </div>
        <div class="form-group">
        	<label for="password">Password:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-key"></i></div>
    			</div>
        		<input class="form-control" type="password" name="password" id="password2" required  minlength="6">
        	</div>
        	<small class="form-text text-muted">at least 6 characters</small>
        </div>
        <div class="form-group">
        	<label for="password_rep">Password:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-key"></i></div>
    			</div>
        		<input class="form-control" type="password" name="password_rep" id="password" required  minlength="6">
        	</div>
        </div>
        <div class="form-group">
        	<label for="language">Language:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-language"></i></div>
    			</div>
        		<select class="form-control" name="lang" id="language" required>
        			<?php foreach(SUPPORTED_LANGUAGES as $key => $lang){ ?>
        				<option value="<?php echo $key; ?>" <?php if($this->getLang()==$key){ echo 'selected'; } ?>><?php echo $lang; ?></option>
        			<?php } ?>
        		</select>
        	</div>
        </div>
		<input class="btn btn-primary" type="submit" name="signup" value="sign up now">
	</div>
</form>