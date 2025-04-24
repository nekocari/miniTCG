			<nav id="top-navigation" class="border-bottom">
				
				<!-- Sidebar Toogle Button -->
				<div id="sidebar-open" class="">
					<a class="btn btn-outline-dark" href="#" onclick="return toggleSidebar()">
						<i class="fas fa-bars"></i>
					</a>
				</div>
				
				<!-- T H E M E - M O D E  toggle -->
				<div class="mx-3">
					<a href="#" class="badge rounded-pill text-bg-dark theme-toggle toggle-dark" onclick="return setTheme('dark')">
						<i class="fas fa-moon pe-md-1"></i> <span class="d-none d-md-inline">dark mode</span>
					</a>
					<a href="#" class="badge rounded-pill text-bg-light theme-toggle toggle-light" onclick="return setTheme('light')">
						<i class="fas fa-sun pe-md-1"></i> <span class="d-none d-md-inline">light mode</span>
					</a>
				</div>
			
				<!-- LOGGED IN -->
				<?php if($this->login->isLoggedIn()) { ?>
					<nav class="ms-auto">
						<ul class="nav">
							<li class="nav-item d-none d-sm-block">
								<a class="nav-link" href="<?php echo Routes::getUri('member_cardmanager');?>">
									<span class="icon"><i class="fas fa-folder-open" title="Card Manager"></i></span>
									<span class="d-none d-xl-inline">Cards</span>
								</a>
							</li>
							<li class="nav-item d-none d-sm-block">
								<a class="nav-link" href="<?php echo Routes::getUri('messages_recieved');?>">
									<span class="icon">
										<i class="fas fa-envelope-open" title="Messages"></i>
										<?php if(($msg_count = count(Message::getReceivedByMemberId($this->login->getUserId(),'new'))) > 0){ ?>
											<span class="notification">
											  <span class="visually-hidden">new Messages</span>
											</span>
										<?php } ?>
									</span>
									<span class="d-none d-xl-inline">Messages</span>
								</a>
							</li>
							<li class="nav-item d-none d-sm-block">
								<a class="nav-link" href="<?php echo Routes::getUri('trades_recieved');?>">
									<span class="icon">
										<i class="fas fa-sync-alt" title="Trade Offers"></i>
										<?php if(($trade_count = count(Trade::getRecievedByMemberId($this->login->getUserId(),'new'))) > 0){ ?>
											<span class="notification">
											  <span class="visually-hidden">new Trade Offers</span>
											</span>
										<?php } ?>
									</span>
									<span class="d-none d-xl-inline">Trade Offers</span>
								</a>
							</li>
							
							<li class="nav-item d-none d-sm-block">
								<div class="vr d-flex h-100 mx-1"></div>
							</li>

							<li id="user-dropdown" class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#"  id="memberDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="icon">
										<i class="fas fa-user-circle"></i>
										<?php if($trade_count > 0 OR $msg_count > 0){ ?>
										<span class="notification d-sm-none">
											<span class="visually-hidden">new Messages</span>
										</span>
										<?php } ?>
									</span>
									<span class="">
										<div class="username"><?php echo $this->login->getUser()->getName(); ?></div>
										<div class="subtext">
											<?php echo $this->login->getUser()->getLevel('object')->getName(); ?>
											<?php // echo " &bull; ".$this->login->getUser()->getMoney()." ".Setting::getByName('currency_name')->getValue(); ?>
										</div>
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-end" aria-labelledby="memberDropdown" style="min-width:250px">
									
									<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('member_dashboard');?>">
										<span><i class="fas fa-house-user me-md-1"></i> Memberarea</span>
									</a>
									
									<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('member_cardmanager');?>">
										<span><i class="fas fa-folder-open me-1"></i> Card Manager</span>
									</a>
										
									<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('messages_recieved');?>">
										<span><i class="fas fa-envelope-open me-1"></i>Messages</span>
										<?php if($msg_count > 0){ ?>
											<span class="badge bg-pill bg-dark"><?php echo $msg_count; ?></span>
										<?php } ?>
									</a>
									
									<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('trades_recieved');?>">
										<span><i class="fas fa-sync-alt me-1"></i>Trade Offers</span>
										<?php if($trade_count > 0){ ?>
											<span class="badge bg-pill bg-dark"><?php echo $trade_count; ?></span>
										<?php } ?>
									</a>

									<div class="dropdown-divider"></div>
									
									
									<div class="dropdown-item">
										<a class=" btn btn-outline-dark d-block" href="<?php echo Routes::getUri('signout');?>">
											<i class="fas fa-sign-out-alt me-lg-1"></i> <span class="">Logout</span>
										</a>
									</div>
									<!-- Link to ACP -->
									<?php if(count($this->login->getUser()->getRights()) > 0){ ?>
										<div class="dropdown-item">
											<a class="btn btn-dark d-block" href="<?php echo Routes::getUri('admin_dashboard');?>">
											<i class="fas fa-lock me-lg-1"></i> <span class="">Administration</span></a>
										</div>
									<?php } ?>
								</div>
							</li>
						</ul>
					</nav>
				<?php } ?>
				
				<!-- NOT LOGGED IN -->
				<?php if(!$this->login->isLoggedIn()) { ?>
					
						<ul class="nav ms-auto me-2 my-2">
							<li class="nav-item me-2">
								<a class="btn btn-dark" href="<?php echo Routes::getUri('signup');?>">
									<i class="fas fa-pencil-alt me-1"></i> 
									<span class="d-none d-md-inline">Sign Up</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="btn btn-primary" href="<?php echo Routes::getUri('signin');?>">
									<i class="fas fa-sign-in-alt me-1"></i> 
									<span class="d-none d-md-inline">Sign In</span>
								</a>
							</li>
						</ul>
						
				<?php } ?>
			
			</nav>