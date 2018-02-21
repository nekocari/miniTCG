<html lang="de">

	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
		<base href="<?php echo BASE_URI; ?>">
		<title>miniTCG</title>
		
		<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    	<!-- Custom CSS -->
		<!--<link rel="stylesheet" type="text/css" href="css/style.css">-->
		<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
	</head>

	<body>
		
        <nav class="navbar navbar-expand navbar-dark bg-dark">
        	<div class="container">
        		<a class="navbar-brand" href="<?php echo BASE_URI; ?>">miniTCG</a>
        	
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                	<span class="navbar-toggler-icon"></span>
                </button>
    			
    			<div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
        				<li class="nav-item active">
        					<a class="nav-link" href="<?php echo BASE_URI; ?>"><i class="fas fa-home"></i> Startseite</a>
        				</li>
        				<li class="nav-item active">
        					<a class="nav-link" href="memberlist.php"><i class="fas fa-user"></i> Mitglieder</a>
        				</li>
        				<li class="nav-item active">
        					<a class="nav-link" href="cardsets.php"><i class="fas fa-folder"></i> Karten</a>
        				</li>
        			</ul>
    			</div>
        	</div>
        </nav>
        
		<div class="container">
    		<div class="row my-4">
    		
        		<div class="col-3" id="nav">
        			<div class="list-group">
        				<a class="list-group-item" href="signup.php">Registrierung</a>
        				<a class="list-group-item" href="member/dashboard.php">Mitgliedsbereich</a>
        			</div>
        			
        			<div class="card my-4">
        				<div class="card-header">Mitglieder online</div>
        				<div class="card-body">
        					<?php require_once PATH.'inc/member_online.php'; ?>
        				</div>
        			</div>
        		</div>
	
				<div class="col" id="content">