
<form class="card mx-auto my-3" style="max-width:400px;" name="signInForm" method="POST" action="" role="form">
	<div class="card-header">Einloggen</div>
	<div class="card-body">
        <div class="form-group">
        	<label for="login-username">Benutzername:</label>
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
        	<a href="<?php echo RoutesDb::getUri('lost_password'); ?>">Passwort vergessen?</a><br>
        	oder<br>
        	Noch kein Account? <a href="<?php echo RoutesDb::getUri('signup'); ?>">Jetzt registrieren!</a>
        </div>
	</div>
    	
</form>

