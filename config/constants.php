<?php
    // replace with your values
    define('MYSQL_HOST',     'localhost'); 			// Host Name, meist passt localhost
    define('MYSQL_USER',     'root'); 				// Benutzername
    define('MYSQL_PASS',     '');		 			// Benutzerpasswort
    define('MYSQL_DATABASE', 'miniTCG');	 		// Datenbank Name
    
    define('BASE_URI', 'http://localhost/eclipse/miniTCG/'); // Pfad wo die App installiert wurde
    
    define('DEFAULT_TIMEZONE','Europe/Berlin');
    define('SUPPORTED_LANGUAGES',['de','en']);
    
    // no need to edit here
    define('APP_VERSION','v0.2alpha');
    define('PATH', dirname(__FILE__).'/../'); 		// relativer Pfad
    define('SERVER_URL', $_SERVER['SERVER_NAME']);
    define('ERROR_LOG', PATH.'app_error.log');      // to be used in later versions
    
    
 
?>