<?php
class Db {
    private static $instance = NULL;
    
    private function __construct() {}
    
    private function __clone() {}
    
    public static function getInstance() {
        if (!isset(self::$instance)) {
            try {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instance = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DATABASE.';charset=utf8', MYSQL_USER, MYSQL_PASS, $pdo_options);
            }catch (PDOException $e){
            	error_log(date('Y-m-d H:i:s').' -- file: '.$e->getFile().' line:'.$e->getLine().' -- '.$e->getCode().' - '.$e->getMessage().PHP_EOL,3,ERROR_LOG);	
                die('No database connection. Error report sent to log.');
            }
        }
        return self::$instance;
    }
}
?>