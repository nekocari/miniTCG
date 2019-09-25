<?php
    // replace with your values
    define('MYSQL_HOST',     'localhost'); 			// Host Name, meist passt localhost
    define('MYSQL_USER',     'root'); 				// Benutzername
    define('MYSQL_PASS',     '');		 			// Benutzerpasswort
    define('MYSQL_DATABASE', 'miniTCG');	 		// Datenbank Name
    
    define('BASE_URI', 'http://localhost/eclipse/miniTCG/'); // Pfad wo die App installiert wurde
    define('CARDS_FOLDER','img/cards/');            // Ordner an den die Karten hochgeladen werden
    
    
    // no need to edit here
    define('PATH', dirname(__FILE__).'/../'); 		// relativer Pfad
    define('SERVER_URL', $_SERVER['SERVER_NAME']);
 
?>