<?php
    // replace with your values
    define('MYSQL_HOST',     'localhost'); 			// host name
    define('MYSQL_USER',     'root'); 				// username
    define('MYSQL_PASS',     '');		 			// password
    define('MYSQL_DATABASE', 'miniTCG');	 		// database ame
    
    define('BASE_URI', 'http://localhost/eclipse/miniTCG/'); // base path / Basispfad
    
    define('DEFAULT_TIMEZONE','Europe/Berlin'); 	// default application timezone 
    
    // languages the app supports: (remove what you don't need/want)
    // with German as primary language
    define('SUPPORTED_LANGUAGES',[
    		'de'=>'Deutsch',
    		'en'=>'English'		
    ]);
    /*
    // with English as primary language:
    define('SUPPORTED_LANGUAGES',[
    		'en'=>'English',
    		'de'=>'Deutsch'
    ]);
    */
    
    
    /* 
     * IMPORTANT INFO
     * --------------
     * Feel free to add more languages!
     * To do so, add a new key value pair to the array above. (e.g. 'fr'=>'Français')
     * Copy the view files form another language folder into a new folder, named after your new key, 
     * and translate the texts.
     * You might also want to add your new language to the system messages
     */
    
    
    
    
    
    
    
    // no need to edit here -------------------------------------------------------------------------------------
    define('APP_VERSION','v1.0beta');
    define('PATH', dirname(__FILE__).'/../'); 		// relativer Pfad
    define('SERVER_URL', $_SERVER['SERVER_NAME']);
    define('ERROR_LOG', PATH.'app_error.log');      // to be used in later versions
    
    
 
?>