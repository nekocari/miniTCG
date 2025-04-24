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
		<link rel="stylesheet" type="text/css" href="public/css/layout3.css">
		
	    <!-- additional css -->
	    <?php echo $this->getCssLinks(); ?>
		
	</head>
	<body id="design3">
		
		<?php
			// include sidebar partial
			include __DIR__."/_sidebar.php";  
		?>
		
		<div id="main-container">
		
			<?php
				// include sidebar partial
				include __DIR__."/_topnavi.php";  
			?>
		

			
			<main class="px-3 px-md-4 px-xl-5">
			<!-- Content -->
			 
			 
			 
    		
		