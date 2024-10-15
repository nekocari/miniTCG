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
		<link rel="stylesheet" type="text/css" href="public/css/layout1.css">
		
	    <!-- additional css -->
	    <?php echo $this->getCssLinks(); ?>
		
	</head>
	<body>
        					
					
    <!-- page wrapper -->
    <div id="page-wrapper" class="container p-0 my-sm-4 d-flex flex-wrap-reverse">
    
    	<!-- left -->
    	<div id="main" class="col">
        	
        	
        	<!-- Main Navigation -->
        	<div id="main-nav">
                <nav class="navbar navbar-expand-sm px-0 py-3">  
					<div class="container-fluid">
                	
						<a class="navbar-brand" href="<?php echo BASE_URI; ?>"><?php if(($name = Setting::getByName('app_name')->getValue()) != 'miniTCG'){ echo $name; }else{ ?>mini<span class="text-primary fw-bold">TCG</span> <small><?php echo APP_VERSION; ?></small><?php } ?></a>        	
						
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						
						<div class="collapse navbar-collapse" id="navbarNav">
							<ul class="navbar-nav ms-auto">
								<li class="nav-item">
									<a class="nav-link" href="<?php echo BASE_URI; ?>"><i class="fas fa-home text-primary"></i> Startseite</a>
								</li>
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
							</ul>
						</div>
					</div>
                </nav>
        	</div>
        	<!-- END Main Navigation -->
        	
        	
    		<!-- Content -->
        	<div id="content" class="mt-4 mx-2">