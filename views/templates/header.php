<html lang="de">

	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
		<base href="<?php echo BASE_URI; ?>">
		<title><?php echo $tcg_title; ?></title>
		
		<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    	<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/style.css">
		
		<!-- Fontawesome -->
		<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
		
		<!-- jquery and bootstrap js -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
	</head>
	<body>
		<!-- Main Navigation -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        	<div class="container">
        		<a class="navbar-brand" href="<?php echo BASE_URI; ?>">mini<span class="tcg">TCG</span> <small>v0.1</small></a>
        	
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
                </button>
    			
    			<div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
        				<li class="nav-item">
        					<a class="nav-link" href="<?php echo BASE_URI; ?>"><i class="fas fa-home"></i> Startseite</a>
        				</li>
        				<li class="nav-item">
        					<a class="nav-link" href="<?php echo ROUTES::getUri('member_index');?>"><i class="fas fa-users"></i> Mitglieder</a>
        				</li>
        				<li class="nav-item dropdown">
        					<a class="nav-link dropdown-toggle" href="#"  id="decksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-folder"></i> Karten</a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="decksDropdown">
                            	<!-- PHP Code for the Category List -->
                            	<?php foreach($tcg_categories as $tcg_category){ ?>
									<a class="dropdown-item" href="<?php echo $tcg_category->getLinkUrl();?>"><?php echo $tcg_category->getName();?></a>
								<?php } ?>
                            	<!-- Category Liste End -->
                            	<div class="dropdown-divider"></div>
                            	<a class="dropdown-item" href="<?php echo ROUTES::getUri('deck_index');?>">Alle</a>
                            </div>
        				</li>
        			</ul>
    			</div>
        	</div>
        </nav>
        
        
        <!-- Center of Layout -->
		<div class="container">
    		<div id="main-area" class="row my-4">
    		
        		<!-- Content -->
				<div class="col-12 col-lg-9" id="content">