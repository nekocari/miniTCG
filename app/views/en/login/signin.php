<form class="card mx-auto my-5" style="max-width:400px;" name="signInForm" method="POST" action="" role="form">
	<div class="card-header">Login</div>
	<div class="card-body">
        <div class="mb-3">
        	<label for="login-username" class="form-label">Username:</label>
        	<div class="input-group">
    				<div class="input-group-text"><i class="fas fa-user"></i></div>
        		<input class="form-control" type="text" name="username" id="login-username">
        	</div>
        </div>
        <div class="mb-3">
        	<label for="login-password" class="form-label">Password:</label>
        	<div class="input-group">
    				<div class="input-group-text"><i class="fas fa-key"></i></div>
        		<input class="form-control" type="password" name="password" id="login-password">
        	</div>
        </div>
        
        <p class="text-center"><input class="btn btn-primary" type="submit" name="signIn" value="login"></p>
        <hr>
        <div class="text-center text-muted small">
        	<a href="<?php echo Routes::getUri('lost_password'); ?>">Lost password?</a><br>
        	or<br>
        	No account yet? <a href="<?php echo Routes::getUri('signup'); ?>">Sign up!</a>
        </div>
	</div>
    	
</form>

