<?php
/** 
 * SETUP for minTCG
 */


// set up database connection
require_once '../config/constants.php';
require_once '../core/dbconnect.php';
require_once '../helper/text_processing.php';


if(isset($_POST['admin_user']) AND isset($_POST['admin_password']) AND !empty($_POST['admin_user'] AND !empty($_POST['admin_password']))){
	
	
	try{
		$db = Db::getInstance();
		
		// create tables
		$mysql = file_get_contents(PATH.'setup/structure2023.sql');
		$req = $db->query($mysql);
		$admin_name = ""
		$admin_query = "
		INSERT INTO `members` (`id`, `name`, `mail`, `password`, `join_date`) VALUES
		(1, 'Admin', 'admin@minitcg', '$2y$10$yq/CFMqDu8GVIK5F6QQ/7Oz5dUjdgsfirhOf0jaCh6.5Acrpt.Y2e','2018-02-20 07:40:09');
		
		CREATE TABLE `members_online` (
				`member` int(10) UNSIGNED NOT NULL,
				`ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
				
				CREATE TABLE `members_rights` (
						`member_id` int(10) UNSIGNED NOT NULL,
						`right_id` int(10) UNSIGNED NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
						
						INSERT INTO `members_rights` (`member_id`, `right_id`) VALUES
						(1, 1);
		";
		$req2 = $db->query($admin_query);
		// TODO: check folder for updates and add them in in a loop.
		// TODO: check for actual success of query
		
		$setup_result_message= '<p>Tabellen erfolgreich angelegt</p>'.
				'<p>Benutzer und Passwort für den Administrationsbereich lauten: User <b>Admin</b> Passwort <b>admin</b>.</p>'.
				'<p>Bitte ändere das Passwort direkt nach dem ersten Login!</p>';
		
		
	}
	catch (PDOException $e){
		
		die('Setup Fehlgeschlagen! Error Message: '.$e->getMessage());
		
	}
}else{
	
	$setup_result_message = '<div style="max-width: 500px;" class="mx-auto">'.
			'<form name="start-setup" method="POST" action="">'.
			'	<div class="row justify-content-center my-2">'.
			'		<div class="col-12">'.
			'			Admin User'.
			'		</div>'.
			'		<div class="col-12">'.
			'			<input class="form-control" type="text" name="admin_user">'.
			'		</div>'.
			'	</div>'.
			'	<div class="row justify-content-center my-2">'.
			'		<div class="col-12">'.
			'			Admin Password'.
			'		</div>'.
			'		<div class="col-12">'.
			'			<input class="form-control" type="password" name="admin_password">'.
			'		</div>'.
			'	</div>'.
			'	<p class="text-center"><input class="btn btn-primary" type="submit" name="start" value="start setup"></p>'.
			'</form></div>';
	
}


require_once 'design.php';

?>