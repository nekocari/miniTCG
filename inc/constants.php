<?php
error_reporting(E_ALL);
if($_SERVER['SERVER_NAME'] == 'localhost'){
    // ------------------------------------------------------------------------------ L O C A L
    define('MYSQL_HOST',     'localhost'); 			// Host Name, meist passt localhost
    define('MYSQL_USER',     'root'); 				// Benutzername
    define('MYSQL_PASS',     '');		 			// Benutzerpasswort
    define('MYSQL_DATABASE', 'miniTCG');	 		// Datenbank Name
    
    define('PROJECT_FOLDER','eclipse/miniTCG');
    define('PATH', dirname(__FILE__).'/../'); 				// relativer Pfad
    define('BASE_URI', 'http://localhost/'.PROJECT_FOLDER.'/'); 			// absoluter Pfad
    define('SERVER_URL', $_SERVER['SERVER_NAME']);
}
?>