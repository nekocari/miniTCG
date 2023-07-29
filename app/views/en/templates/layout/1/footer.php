</div>
        	
        	<div id="footer" class="text-center">
        		<hr class="m-2">
    			<small class="d-block m-2">
    				<a href="<?php echo Routes::getUri('faq'); ?>">FAQ</a> &bull; <a href="<?php echo Routes::getUri('imprint'); ?>">Imprint</a><br> 
    				powered by <a href="https://github.com/nekocari/miniTCG/" target="_blank">miniTCG <small><?php echo APP_VERSION; ?></small></a>
    			</small>
        	</div>
    	</div>
    	<!-- Content end -->
    	
    	
    	
    	<!-- right -->
    	<div id="member-nav" class="col-lg-4 col-12 ms-lg-5  py-1 pb-lg-5 pt-lg-4 px-xl-5 px-lg-4 px-md-3 px-2 border-rounded bg-body-secondary bg-opacity-50">
    	
    		<div class="d-block">
    			<!-- Navigation if IS logged in -->  
            	<?php if($this->login->isLoggedIn()) { ?>  
				<div class="text-right d-none d-lg-block">
					<a href="#" class="theme-toggle toggle-dark badge rounded-pill bg-dark" onclick="return setTheme('dark')">
						<i class="fas fa-moon"></i>
						<span class="">dark mode</span>
					</a>
					<a href="#" class="theme-toggle toggle-light badge rounded-pill bg-light text-dark" onclick="return setTheme('light')">
						<i class="fas fa-sun"></i>
						<span class="">light mode</span>
					</a>
				</div>
    			<div class="m-0 my-lg-2 d-flex flex-wrap d-lg-block justify-content-between align-items-center">
    				<span class="h5 m-0 text-nowrap">Hello <b><a href="<?php echo $this->login->getUser()->getProfilLink(); ?>"><?php echo $this->login->getUser()->getName(); ?></a></b></span>
    				
    				<div class="text-nowrap">					
						<a href="#" class="d-lg-none theme-toggle toggle-dark badge rounded-pill bg-dark" onclick="return setTheme('dark')">
							<i class="fas fa-moon"></i> dark
						</a>
						<a href="#" class="d-lg-none theme-toggle toggle-light badge rounded-pill bg-light text-dark" onclick="return setTheme('light')">
							<i class="fas fa-sun"></i> light
						</a>
	    				<a class="ms-1 btn btn-dark d-lg-none" href="<?php echo Routes::getUri('signout');?>">
	    						<i class="fas fa-sign-out-alt me-lg-1"></i> <span class="d-lg-inline d-none">Logout</span>
	    				</a>
	    				<?php if(count($this->login->getUser()->getRights()) > 0){ ?>
		    				<a class=" btn btn-primary d-lg-none" href="<?php echo Routes::getUri('admin_dashboard');?>">
		    					<i class="fas fa-lock me-lg-1"></i> <span class="d-none d-lg-inline">Administation</span>
		    				</a>
	    				<?php } ?>
	    			</div>
    			</div>
    			<ul class="mt-1 nav nav-fill flex-lg-column justify-content-end justify-content-md-between" id="member-links">
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('member_dashboard');?>">
        					<span><i class="fas fa-house-user me-md-1"></i>&nbsp;<span class="d-none d-md-inline">Memberarea</span></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('member_cardmanager');?>">
    						<span><i class="fas fa-folder-open me-1"></i> <span class="d-none d-md-inline me-2">Card Manager</span></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('messages_recieved');?>">
    						<span><i class="fas fa-envelope-open me-1"></i> <span class="d-none d-md-inline me-2">Messages</span></span> 
    						<?php $counter = count(Message::getReceivedByMemberId($this->login->getUserId(),'new')); 
    						if($counter > 0){ ?>
    							<span class="badge bg-pill bg-dark"><?php echo $counter; ?></span>
    						<?php } ?>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('trades_recieved');?>">
    						<span><i class="fas fa-sync-alt me-1"></i> <span class="d-none d-md-inline me-2">Trade Offers</span></span>
    						<?php $counter = count(Trade::getRecievedByMemberId($this->login->getUserId(),'new')); 
    						if($counter > 0){ ?>
    							<span class="badge bg-pill bg-dark"><?php echo $counter; ?></span>
    						<?php } ?>
    					</a>
    				</li>
    				<li class="nav-item my-md-2 d-none d-lg-block">
    					<a class=" btn btn-dark d-lg-block" href="<?php echo Routes::getUri('signout');?>">
    						<i class="fas fa-sign-out-alt me-lg-1"></i> <span class="d-lg-inline d-none">Logout</span>
    					</a>
    				</li>
    				<!-- Link to ACP -->
    				<?php if(count($this->login->getUser()->getRights()) > 0){ ?>
    				<li class="nav-item my-md-2 ms-2 ms-lg-0  d-none d-lg-block">
    					<a class="btn btn-primary d-lg-block" href="<?php echo Routes::getUri('admin_dashboard');?>">
    					<i class="fas fa-lock me-lg-1"></i> <span class="d-none d-lg-inline">Administation</span></a>
    				</li>
    				<?php } ?>
    				<!-- END Link to ACP -->
    			</ul>	
    			<!-- END logged in -->
    			
    			
    			<!-- Navigation if NOT logged in -->
    			<?php }else{ ?>
				<div class="text-right d-none d-lg-block">
					<a href="#" class="theme-toggle toggle-dark badge rounded-pill bg-dark" onclick="return setTheme('dark')">
						<i class="fas fa-moon"></i>
						<span class="d-none d-lg-inline">dark mode</span>
					</a>
					<a href="#" class="theme-toggle toggle-light badge rounded-pill bg-light text-dark" onclick="return setTheme('light')">
						<i class="fas fa-sun"></i>
						<span class="d-none d-lg-inline">light mode</span>
					</a>
				</div>
    			<div class="m-0 mb-lg-2 d-flex flex-wrap d-lg-block justify-content-between align-items-center">
    				<span class="h5 m-0 text-nowrap">Hello <b>Guest</b></span>
					<a href="#" class="d-lg-none theme-toggle toggle-dark badge rounded-pill bg-dark" onclick="return setTheme('dark')">
						<i class="fas fa-moon"></i> dark
					</a>
					<a href="#" class="d-lg-none theme-toggle toggle-light badge rounded-pill bg-light text-dark" onclick="return setTheme('light')">
						<i class="fas fa-sun"></i> light
					</a>
    			</div>
    			<ul class="nav nav-fill flex-lg-column align-items-center" id="member-guest">
    				<li class="nav-item">
    					<a class="nav-link" href="<?php echo Routes::getUri('signup');?>"><i class="fas fa-pencil-alt me-md-1"></i> Register</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link" href="<?php echo Routes::getUri('signin');?>"><i class="fas fa-sign-in-alt me-md-1"></i> Login</a>
    				</li>
    			</ul>
    			<?php } ?>
    			<!-- END not logged in -->
			</div>
				
			<hr class="d-none d-lg-block m-4">
			
			<!-- Members online -->
			<div class="d-block">
    			<div class="h5 d-none d-lg-flex">
    				<?php echo count(MemberOnline::getVisible($this->login->getUser())); ?> Members online
    			</div>
				<div class="text-right">
					<button class="btn btn-sm btn-outline-dark dropdown-toggle py-0 mb-1 d-lg-none d-inline-block" data-bs-toggle="collapse" data-bs-target="#members-online" aria-expanded="false" aria-controls="members-online">
						<?php echo count(MemberOnline::getVisible($this->login->getUser())); ?> Members online
					</button>
				</div>
    			<div id="members-online" class="card collapse d-lg-block">
    				<div class="card-body p-1 p-lg-3">
    					<?php if(count($mo = MemberOnline::getVisible($this->login->getUser()))){ 
    						foreach($mo as $m){ ?>
    							<a class="btn btn-link" href="<?php echo $m->getMember()->getProfilLink(); ?>"><i class="fas fa-user"></i> <?php echo $m->getMember()->getName(); ?></a>
    						<?php } 
    					}else{ ?> 
    						no none is online 
    					<?php } ?>
    				</div>
    			</div>
			</div>
			<!-- END Members online -->
    	</div>
    	<!-- END right -->
    	
    	
    </div>
    
    
    <!-- custom jquery and javascript -->
    <?php echo $this->getJsLinks(); ?>
		
			
	</body>
</html>