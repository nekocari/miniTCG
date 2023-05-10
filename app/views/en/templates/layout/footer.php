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
    	<div id="member-nav" class="col-lg-4 col-12 py-1 py-lg-5 px-xl-5 px-lg-4 px-md-3 px-sm-2 border-rounded">
    	
    		<div class="d-block">
    		
    			<!-- Navigation if IS logged in -->  
            	<?php if($this->login->isLoggedIn()) { ?>  
    			<div class="m-0 mb-lg-2 d-flex flex-wrap d-lg-block justify-content-between align-items-center">
    				<span class="h5 m-0 text-nowrap">Hello <b><a href="<?php echo $this->login->getUser()->getProfilLink(); ?>"><?php echo $this->login->getUser()->getName(); ?></a></b></span>
    				
    				<div class="text-nowrap">
	    				<a class=" btn btn-dark d-lg-none" href="<?php echo Routes::getUri('signout');?>">
	    						<i class="fas fa-sign-out-alt mr-lg-1"></i> <span class="d-lg-inline d-none">Logout</span>
	    				</a>
	    				<?php if(count($this->login->getUser()->getRights()) > 0){ ?>
		    				<a class=" btn btn-primary d-lg-none" href="<?php echo Routes::getUri('admin_dashboard');?>">
		    					<i class="fas fa-lock mr-lg-1"></i> <span class="d-none d-lg-inline">Administation</span>
		    				</a>
	    				<?php } ?>
	    			</div>
	    		</div>
    			<ul class="nav nav-fill flex-lg-column justify-content-between" id="member-links">
    				<li class="nav-item">
    					<a class="nav-link text-left" href="<?php echo Routes::getUri('member_dashboard');?>">
        					<span><i class="fas fa-house-user mr-md-1"></i>&nbsp;<span class="d-none d-md-inline">Member Area</span></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('member_cardmanager');?>">
    						<span><i class="fas fa-folder-open mr-1"></i> <span class="d-none d-md-inline mr-2">Card Manager</span></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('messages_recieved');?>">
    						<span><i class="fas fa-envelope-open mr-1"></i> <span class="d-none d-md-inline mr-2">Messages</span></span> 
    						<span class="badge badge-pill badge-dark"><?php echo count(Message::getReceivedByMemberId($this->login->getUserId(),'new')); ?></span>
    					</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link d-md-flex justify-content-lg-between align-items-center" href="<?php echo Routes::getUri('trades_recieved');?>">
    						<span><i class="fas fa-sync-alt mr-1"></i> <span class="d-none d-md-inline mr-2">Trade Offers</span></span>
    						<span class="badge badge-pill badge-dark"><?php echo count(Trade::getRecievedByMemberId($this->login->getUserId(),'new')); ?></span>
    					</a>
    				</li>
    				<li class="nav-item my-lg-2 d-none d-lg-block">
    					<a class="nav-link btn btn-dark" href="<?php echo Routes::getUri('signout');?>">
    						<i class="fas fa-sign-out-alt mr-lg-1"></i> <span class="d-lg-inline d-none">Logout</span>
    					</a>
    				</li>
    				<!-- Link to ACP -->
    				<?php if(count($this->login->getUser()->getRights()) > 0){ ?>
    				<li class="nav-item my-lg-2 ml-2 ml-lg-0 d-none d-lg-block">
    					<a class="nav-link btn btn-primary" href="<?php echo Routes::getUri('admin_dashboard');?>">
    					<i class="fas fa-lock mr-lg-1"></i> <span class="d-none d-lg-inline">Administation</span></a>
    				</li>
    				<?php } ?>
    				<!-- END Link to ACP -->
    			</ul>	
    			<!-- END logged in -->
    			
    			
    			<!-- Navigation if NOT logged in -->
    			<?php }else{ ?>
    			<div class="m-0 mb-lg-2 d-flex flex-wrap d-lg-block justify-content-between align-items-center">
    				<span class="h5 m-0 text-nowrap">Hello <b>Guest</b></span>
    				<button class="btn btn-sm btn-outline-dark dropdown-toggle py-0 d-lg-none d-block" data-toggle="collapse" data-target="#members-online" aria-expanded="false" aria-controls="members-online">
    					<small><?php echo count(MemberOnline::getVisible($this->login->getUser())); ?> Members online</small>
    				</button>
    			</div>
    			<ul class="nav nav-fill flex-lg-column align-items-center" id="member-guest">
    				<li class="nav-item">
    					<a class="nav-link" href="<?php echo Routes::getUri('signup');?>"><i class="fas fa-pencil-alt mr-md-1"></i> Register</a>
    				</li>
    				<li class="nav-item">
    					<a class="nav-link" href="<?php echo Routes::getUri('signin');?>"><i class="fas fa-sign-in-alt mr-md-1"></i> Login</a>
    				</li>
    			</ul>
    			<?php } ?>
    			<!-- END not logged in -->
			</div>
				
			<hr class="d-none d-lg-block m-4">
			
			<!-- Members online -->
			<div class="d-block">
    			<div class="h5 d-none d-lg-flex">
    				<?php echo count(MemberOnline::getVisible()); ?> Members online
    			</div>
				<div class="text-right">
		    		<button class="btn btn-sm btn-outline-dark dropdown-toggle py-0 mb-1 d-lg-none d-inline-block" data-toggle="collapse" data-target="#members-online" aria-expanded="false" aria-controls="members-online">
		    			<small><?php echo count(MemberOnline::getVisible($this->login->getUser())); ?> Members online</small>
	    		</button>
	    		</div>	  
    			<div class="d-none  text-right mb-1">
    				<button class="btn btn-sm btn-outline-dark dropdown-toggle py-0" data-toggle="collapse" data-target="#members-online" aria-expanded="false" aria-controls="members-online">
    					<small><?php echo count(MemberOnline::getVisible()); ?> Members online</small>
    				</button>
    			</div>
    			<div id="members-online" class="card collapse d-lg-block">
    				<div class="card-body p-1 p-lg-3">
    					<?php if(count($mo = MemberOnline::getVisible())){ 
    						foreach($mo as $m){ ?>
    							<a class="btn btn-link" href="<?php echo $m->getMember()->getProfilLink(); ?>"><i class="fas fa-user"></i> <?php echo $m->getMember()->getName(); ?></a>
    						<?php } 
    					}else{ ?> 
    						no one is online 
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