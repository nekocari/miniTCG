<html lang="en">

	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta name="description" content="<?php echo Setting::getByName('app_meta_description')->getValue(); ?>">
    	
		<base href="<?php echo BASE_URI; ?>">
		<title><?php echo Setting::getByName('app_name')->getValue(); ?></title>
		
    	<!-- Customized Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="public/css/<?php echo (Setting::getByName('app_theme')->getValue() ?? 'default.css'); ?>">
    	<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="public/css/style.css">
		
		<!-- Fontawesome -->
		<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
		
		<!-- jquery and bootstrap js -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		
		
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
                <nav class="navbar navbar-expand-sm navbar-light px-0 py-3">   
                	
                	<a class="navbar-brand" href="<?php echo BASE_URI; ?>"><?php if(($name = Setting::getByName('app_name')->getValue()) != 'miniTCG'){ echo $name; }else{ ?>mini<span class="tcg">TCG</span> <small><?php echo APP_VERSION; ?></small><?php } ?></a>        	
                                       
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    	<span class="navbar-toggler-icon"></span>
                    </button>
        			
        			<div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
            				<li class="nav-item">
            					<a class="nav-link" href="<?php echo BASE_URI; ?>"><i class="fas fa-home text-primary"></i> Homepage</a>
            				</li>
            				<li class="nav-item">
            					<a class="nav-link" href="<?php echo Routes::getUri('member_index');?>"><i class="fas fa-users text-primary"></i> Members</a>
            				</li>
            				<li class="nav-item dropdown">
            					<a class="nav-link dropdown-toggle" href="#"  id="decksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-folder text-primary"></i> Cards</a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="decksDropdown">
                                	<!-- PHP Code for the Category List -->
                                	<?php if(count($tcg_categories = Category::getAll(['name'=>'ASC']))>0){ foreach($tcg_categories as $tcg_category){ ?>
        								<a class="dropdown-item" href="<?php echo $tcg_category->getLinkUrl();?>"><?php echo $tcg_category->getName();?></a>
        							<?php } ?>
                                	<div class="dropdown-divider"></div>
                                	<?php } ?>
                                	<!-- Upcoming -->
                                	<?php if(Setting::getByName('cards_decks_upcoming_public')->getValue() == 1){ ?>
                                		<a class="dropdown-item" href="<?php echo Routes::getUri('decks_list_upcoming');?>">Upcoming</a>
                                	<?php } ?>
                                	<!-- all -->
                                	<a class="dropdown-item" href="<?php echo Routes::getUri('deck_index');?>">All</a>
                                	<!-- end of list -->
                                </div>
            				</li>
            			</ul>
        			</div>
                </nav>
        	</div>
        	<!-- END Main Navigation -->
        	
        	
    		<!-- Content -->
        	<div id="content">