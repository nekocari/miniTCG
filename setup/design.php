<html lang="de">

	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
		<base href="<?php echo BASE_URI; ?>">
		<title>miniTCG</title>
		
		<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    	<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/style.css">
		
	</head>
	<body>
		<!-- Main Navigation -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        	<div class="container">
        		<a class="navbar-brand" href="<?php echo BASE_URI; ?>">mini<span class="tcg">TCG</span> <small>v0.1</small> SETUP</a>
        	</div>
        </nav>
        
        
        <!-- Center of Layout -->
		<div class="container">
    		<div id="main-area" class="row my-4">
    		
        		<!-- Content -->
				<div class="col-12 col-lg-9" id="content">
    				<p><?php echo $setup_result_message; ?></p>
    				
    				<?php if(isset($errors)){ foreach($errors as $file => $e){ ?>
        				<!-- <?php  echo 'Fehler --'.$file.' -- '.$e.'';  ?> -->
        			<?php }} ?>
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