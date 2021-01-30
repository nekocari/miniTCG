<?php
/** 
 * SETUP for minTCG
 */


// set up database connection
require_once '../inc/constants.php';
require_once '../inc/dbconnect.php';

try{
    $db = Db::getInstance();
    
    // create tables
    $mysql = file_get_contents(PATH.'setup/structure.sql');
    $req = $db->query($mysql);
    // TODO: check folder for updates and add them in in a loop.
    // TODO: check for actual success of query
    
    $setup_result_message= '<p>Tabellen erfolgreich angelegt</p>'.
        '<p>Benutzer und Passwort für den Administrationsbereich lauten: User <b>Admin</b> Passwort <b>admin</b>.</p>'.
        '<p>Bitte ändere das Passwort direkt nach dem ersten Login!</p>';
    require_once 'design.php';
    
    
}
catch (PDOException $e){
    
    die('Setup Fehlgeschlagen! Error Message: '.$e->getMessage());
    
}
?>