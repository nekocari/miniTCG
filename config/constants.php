<?php
    // replace with your values
    define('MYSQL_HOST',     'localhost'); 			// host name
    define('MYSQL_USER',     'root'); 				// username
    define('MYSQL_PASS',     '');		 			// password
    define('MYSQL_DATABASE', 'miniDevTCG');	 		// database ame
    
    define('BASE_URI', 'http://localhost/eclipse/miniTCG/'); // base path, keep the '/' at the end(!) even with top level domain (e.g. https://domain.com/)
    
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
     * Copy the view files you want to translate, form another language folder into a new folder, named after your new key, 
     * and translate the texts.
     * You might also want to add your new language to the system messages (config/system_messages.php)
     */
    
    
    
    
    
    
    
    // no need to edit here -------------------------------------------------------------------------------------
    define('APP_VERSION','v1.3.1.1 beta');
    define('PATH', dirname(__FILE__).'/../'); 					// relative path
    define('DEFAULT_FOLDER_PAGES','app/views/[lang]/pages/'); 	// default path for pages without controller setup
    define('SERVER_URL', $_SERVER['SERVER_NAME']);				// current server
    define('ERROR_LOG', PATH.'app_error.log');      			// to be used more in later versions
    define('SESSION_PREFIX','mtcg');							// change in case of multtiple installs in different folders
    
    
 
?>