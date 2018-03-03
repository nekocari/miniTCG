				</div>
				
				<!-- SIDEBAR -->
				<div class="col-12 col-lg-3" id="nav">
				
        			<?php if(!isset($_SESSION['user'])) { ?>
        			<!-- Navigation if NOT logged in -->
        			<div class="card my-4">
        				<div class="card-header">Hallo <b>Gast</b></div>
    					<div class="list-group list-group-flush">
            				<a class="list-group-item" href="<?php echo ROUTES::getUri('signup');?>">Registrierung</a>
            				<a class="list-group-item" href="<?php echo ROUTES::getUri('signin');?>">Einloggen</a>
            			</div>
        			</div>
        			
        			<?php }else{ ?>
        			<!-- Navigation if logged in -->
        			<div class="card my-4">
        				<div class="card-header">Hallo <b><?php echo $_SESSION['user']->name; ?></b></div>
    					<div class="list-group list-group-flush">
    						<a class="list-group-item" href="<?php echo ROUTES::getUri('member_dashboard');?>">Mitgliedsbereich</a>
            				<a class="list-group-item" href="<?php echo ROUTES::getUri('signout');?>">Ausloggen</a>
    					</div>
        			</div>
        				
        			<?php } ?>
        			
        			<div class="card my-4">
        				<div class="card-header">Mitglieder online</div>
        				<div class="card-body">
        					<?php require_once PATH.'inc/member_online.php'; ?>
        				</div>
        			</div>
        			
        		</div>
        		
			</div>
			
			<!-- Footer -->
			<div class="text-center">
    			<p><hr>
    				miniTCG by <a href="http://www.heavenspell.de">Cari</a><br>
    				<small><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Administration</a></small>
    			</p>
			</div>
		
		</div>  
		
		

	</body>

</html>