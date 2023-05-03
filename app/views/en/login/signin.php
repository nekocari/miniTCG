
<form class="card mx-auto my-3" style="max-width:400px;" name="signInForm" method="POST" action="" role="form">
	<div class="card-header">Sign In</div>
	<div class="card-body">
        <div class="form-group">
        	<label for="login-username">Username:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-user"></i></div>
    			</div>
        		<input class="form-control" type="text" name="username" id="login-username">
        	</div>
        </div>
        <div class="form-group">
        	<label for="login-password">Password:</label>
        	<div class="input-group">
        		<div class="input-group-prepend">
    				<div class="input-group-text"><i class="fas fa-key"></i></div>
    			</div>
        		<input class="form-control" type="password" name="password" id="login-password">
        	</div>
        </div>
        
        <input class="btn btn-primary" type="submit" name="signIn" value="einloggen">
        <hr>
        <div class="text-center text-muted small">
        	<a href="<?php echo Routes::getUri('lost_password'); ?>">Lost password?</a><br>
        	or<br>
        	No account yet? <a href="<?php echo Routes::getUri('signup'); ?>">Sign up!</a>
        </div>
	</div>
    	
</form>

