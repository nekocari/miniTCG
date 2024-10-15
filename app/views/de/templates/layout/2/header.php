<html lang="de">

	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta name="description" content="<?php echo Setting::getByName('app_meta_description')->getValue(); ?>">
    	
		<base href="<?php echo BASE_URI; ?>">
		<title><?php if(empty($this->getTitle())){ echo Setting::getByName('app_name')->getValue(); }else{ echo $this->getTitle(); } ?></title>
		
		<!-- default bootstrap 5 -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    	<!-- Custom Layout CSS -->
		<link rel="stylesheet" type="text/css" href="public/css/layout2.css">
		
	    <!-- additional css -->
	    <?php echo $this->getCssLinks(); ?>
		
	</head>
	<body id="design2">
        					
	
		<!-- Main Navigation -->
		<div id="main-nav" class="bg-dark">
			<nav class="navbar navbar-dark navbar-expand-sm container">  
				<div class="container-fluid">
				
					<a class="navbar-brand" href="<?php echo BASE_URI; ?>"><?php if(($name = Setting::getByName('app_name')->getValue()) != 'miniTCG'){ echo $name; }else{ ?>mini<span class="text-primary fw-bold">TCG</span> <small><?php echo APP_VERSION; ?></small><?php } ?></a>        	
					
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar1" aria-controls="navbar1" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					
					<div class="collapse navbar-collapse" id="navbar1">
						<ul class="navbar-nav ms-auto">
							<li class="nav-item">
								<a class="nav-link" href="<?php echo Routes::getUri('member_index');?>"><i class="fas fa-users text-primary"></i> Mitglieder</a>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#"  id="decksDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-folder text-primary"></i> Karten</a>
								<div class="dropdown-menu dropdown-menu-end" aria-labelledby="decksDropdown">
									<!-- PHP Code for the Category List -->
									<?php if(count($tcg_categories = Category::getAll(['name'=>'ASC'])) > 0){ foreach($tcg_categories as $tcg_category){ ?>
										<a class="dropdown-item" href="<?php echo $tcg_category->getLinkUrl();?>"><?php echo $tcg_category->getName();?></a>
									<?php } ?>
									<div class="dropdown-divider"></div>
									<?php } ?>
									<!-- Upcoming -->
									<?php if(Setting::getByName('cards_decks_upcoming_public')->getValue() == 1){ ?>
										<a class="dropdown-item" href="<?php echo Routes::getUri('decks_list_upcoming');?>">Unveröffentlicht</a>
									<?php } ?>
									<!-- all -->
									<a class="dropdown-item" href="<?php echo Routes::getUri('deck_index');?>">Alle</a>
									<!-- end of list -->
								</div>
							</li>
							<li class="nav-item">
								<div class="vr d-none d-sm-flex h-100 mx-2 text-white"></div>
							</li>
							<li class="nav-item pt-1">
								<a href="#" class="nav-link theme-toggle toggle-dark" onclick="return setTheme('dark')">
									<i class="fas fa-moon"></i> <span class="d-sm-none">dark mode</span>
								</a>
								<a href="#" class="nav-link theme-toggle toggle-light" onclick="return setTheme('light')">
									<i class="fas fa-sun"></i> <span class="d-sm-none">light mode</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<!-- END Main Navigation -->

		
		<!-- START member Navigation -->
		<div id="member-nav" class="bg-secondary-subtle g-gradient">
			<nav class="navbar navbar-expand-sm container">  
				<div class="container-fluid">
					
					<button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#members-online" aria-expanded="false" aria-controls="members-online">
						<?php echo count(MemberOnline::getVisible($this->login->getUser())); ?> <span class="d-none d-md-inline">Mitglieder</span> online
					</button>
					
					
					<!-- LOGGED IN -->
					<?php if($this->login->isLoggedIn()) { ?>  
						<div class="navbar-expand" id="navbar2">
								
							<ul class="mt-1 navbar-nav justify-content-end" id="member-links">
								<li class="nav-item">
									<a class="nav-link d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('member_cardmanager');?>">
										<span class="position-relative"><i class="fas fa-folder-open me-lg-1" title="Karten Manager"></i><span class="d-none d-lg-inline">Karten Manager</span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('messages_recieved');?>">
										<span class="position-relative"><i class="fas fa-envelope-open me-lg-1" title="Nachrichten"></i><span class="d-none d-lg-inline">Nachrichten</span>
										<?php if(($msg_count = count(Message::getReceivedByMemberId($this->login->getUserId(),'new'))) > 0){ ?>
											<span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle">
											  <span class="visually-hidden">neue Nachrichten</span>
											</span>
										<?php } ?>
										</span> 
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('trades_recieved');?>">
										<span class="position-relative"><i class="fas fa-sync-alt me-lg-1" title="Tauschanfragen"></i><span class="d-none d-lg-inline">Tauschanfragen</span>
										<?php if(($trade_count = count(Trade::getRecievedByMemberId($this->login->getUserId(),'new'))) > 0){ ?>
											<span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle">
											  <span class="visually-hidden">neue Taunschanfragen</span>
											</span>
										<?php } ?>
										</span>
									</a>
								</li>
								
								<li class="nav-item">
									<div class="vr d-flex h-100 mx-2"></div>
								</li>

								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#"  id="memberDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-user"></i> <span class="d-none d-md-inline"><?php echo $this->login->getUser()->getName(); ?></span></a>
									<div class="dropdown-menu dropdown-menu-end" aria-labelledby="memberDropdown" style="min-width:250px">
										
										<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('member_dashboard');?>">
											<span><i class="fas fa-house-user me-md-1"></i> Mitgliedsbereich</span>
										</a>
										
										<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('member_cardmanager');?>">
											<span><i class="fas fa-folder-open me-1"></i> Karten Manager</span>
										</a>
											
										<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('messages_recieved');?>">
											<span><i class="fas fa-envelope-open me-1"></i>Nachrichten</span>
											<?php if($msg_count > 0){ ?>
												<span class="badge bg-pill bg-dark"><?php echo $msg_count; ?></span>
											<?php } ?>
										</a>
										
										<a class="dropdown-item d-flex justify-content-between align-items-center" href="<?php echo Routes::getUri('trades_recieved');?>">
											<span><i class="fas fa-sync-alt me-1"></i>Tauschanfragen</span>
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
						</div>
						
					<?php } ?>
					
					<!-- NOT LOGGED IN -->
					<?php if(!$this->login->isLoggedIn()) { ?>  
						<div class="justify-content-end" id="navbar2">
							<ul class="nav">
								<li class="nav-item">
									<a class="btn btn-outline-dark" href="<?php echo Routes::getUri('signin');?>">Login</a>
								</li>
								<li class="nav-item ms-2">
									<a class="btn btn-dark" href="<?php echo Routes::getUri('signup');?>">Registrieren</a>
								</li>
							</ul>
						</div>
					<?php } ?>

				</div>
			</nav>
		</div>

    	<div class="bg-body-secondary bg-opacity-50">

			<!-- Members online -->
			<div id="members-online" class="card collapse container">
				<div class="card-body p-1 p-lg-3">
					<?php if(count($mo = MemberOnline::getVisible($this->login->getUser()))){ 
						foreach($mo as $m){ ?>
							<a class="btn btn-link" href="<?php echo $m->getMember()->getProfilLink(); ?>"><i class="fas fa-user"></i> <?php echo $m->getMember()->getName(); ?></a>
						<?php } 
					}else{ ?> 
						niemand ist online 
					<?php } ?>
				</div>
			</div>
			<!-- END Members online -->

    	</div>
        	
        	
		<!-- Content -->
		<div id="content" class="container mt-4 mx-auto">