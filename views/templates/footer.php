
        	</div>
        	
        	<div id="footer" class="text-center">
        		<hr class="m-2">
    			<small class="d-block m-2">
    				<a href="<?php echo Routes::getUri('faq'); ?>">FAQ</a> &bull; <a href="<?php echo Routes::getUri('imprint'); ?>">Impressum</a><br> 
    				<a href="https://github.com/nekocari/miniTCG/" target="_blank">miniTCG</a> by <a href="https://www.heavenspell.de" target="_blank">Cari</a>
    			</small>
        	</div>
    	</div>
    	<!-- Content end -->
    	
    	<!-- right -->
    	<div id="member-nav" class="col-lg-4 col-12 py-1 py-lg-5 px-xl-5 px-lg-4 px-md-3 px-sm-2 d-flex d-lg-block border-rounded bg-light">
    	
    		<div>
            	<?php if(!Login::loggedIn()) { ?>
    			<!-- Navigation if NOT logged in -->    			
    			<div class="h5">Hallo <b>Gast</b></div>
    			<ul class="nav flex-lg-column" id="member-guest">
    				<li class="nav-item"><a class="nav-link" href="<?php echo ROUTES::getUri('signup');?>">Registrierung</a></li>
    				<li class="nav-item"><a class="nav-link" href="<?php echo ROUTES::getUri('signin');?>">Einloggen</a></li>
    			</ul>
    			
    			<?php }else{ ?>
    			<!-- Navigation if logged in -->
    			<div class="h5 m-0 mb-lg-2">Hallo <b><?php echo Login::getUser()->getName(); ?></b></div>
    			<ul class="nav flex-lg-column justify-content-between" id="member-links">
    				<li class="nav-item">
    					<a class="nav-link d-flex justify-content-between align-items-center" href="<?php echo ROUTES::getUri('member_dashboard');?>">
        					<span>Mitgliedsbereich</span> 
        					<span></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-flex justify-content-between align-items-center" href="<?php echo ROUTES::getUri('messages_recieved');?>">
    						<span>Nachrichten</span> 
    						<span class="badge badge-pill badge-dark"><?php echo $tcg_notifications->getMessageCountNew(); ?></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-flex justify-content-between align-items-center" href="<?php echo ROUTES::getUri('trades_recieved');?>">
    						<span>Tauschanfragen</span>
    						<span class="badge badge-pill badge-dark"><?php echo $tcg_notifications->getTradeCountNew(); ?></span>
    					</a>
    				</li>
    				<li class="nav-item my-2">
    					<a class="nav-link btn btn-dark" href="<?php echo ROUTES::getUri('signout');?>">
    						Ausloggen
    					</a>
    				</li>
    				<!-- Link to ACP if user has any special rights -->
    				<?php if(count(Login::getUser()->getRights()) > 0){ ?>
    				<li class="nav-item my-2">
    					<a class="nav-link btn btn-primary" href="<?php echo ROUTES::getUri('admin_dashboard');?>">Administation</a>
    				</li>
    				<?php } ?>
    			</ul>
    			<?php } ?>
			</div>
				
			<hr class="d-none d-lg-block m-4">
			
			<div>
    			<div class="h5 d-lg-flex d-none ">
    				<?php echo count($tcg_members_online->getOnlineMembers()); ?> Mitglieder online
    			</div>
    			<div id="members-online" class="card collapse d-lg-block">
    				<div class="card-body">
    					<?php $tcg_members_online->display(); ?>
    				</div>
    			</div>
			</div>
    	</div>
    	<!-- END right -->
    	
    	
    </div>
		
			
	</body>
</html>