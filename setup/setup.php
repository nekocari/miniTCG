<?php
/** 
 * SETUP for minTCG
 */


// set up database connection
require_once '../config/constants.php';
require_once '../core/dbconnect.php';
require_once '../core/login.php';
require_once '../core/routes_db.php';
require_once '../helper/parsedown.php';
require_once '../app/models/db_record_model.php';
require_once '../app/models/setting.php';
require_once '../app/models/member.php';
require_once '../app/models/member_setting.php';

$db = Db::getInstance();

$table_req = $db->query("SELECT count(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".MYSQL_DATABASE."' ");
$table_count = $table_req->fetchColumn();


$setup_complete = false;
$setup_result_message = '';

if(($table_count > 0 AND !isset($_POST['admin_user'])) OR isset($_POST['start_setup'])){

	try {
		// create tables
		if($table_count == 0){
			
			// Temporary variable, used to store current query
			$templine = '';
			// Read in entire file
			$lines = file(PATH.'setup/structure_v1_3beta.sql');
			// Loop through each line
			foreach ($lines as $line)
			{
				// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || $line == '')
					continue;
					
					// Add this line to the current segment
					$templine .= $line;
					// If it has a semicolon at the end, it's the end of the query
					if (substr(trim($line), -1, 1) == ';')
					{
						// Perform the query
						$db->query($templine);
						// Reset temp variable to empty
						$templine = '';
					}
			}			
			
			$setup_result_message = '<h3>Database created!</h3>';
			$setup_complete = true;
			
		}elseif($table_count != 31){
			$setup_result_message = '<h1>Something went wrong! You are missing some tables.</h1>';
			
		}else{
			$setup_complete = true;
		}
		
		if($setup_complete){
			
			$req = $db->query("SELECT * FROM members");
			if($req->rowCount() == 0){
			
			
				$lang_options = '';
				foreach (SUPPORTED_LANGUAGES as $key=>$value) {
					$lang_options.= '<option value="'.$key.'">'.$value.'</option>';
				}
				
				$setup_result_message.= '<div style="max-width: 500px;" class="mx-auto">'.
					'<p>Create your admin account:</p>'.
					'<form name="start-setup" method="POST" action="">'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Admin User'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="text" name="admin_user" required>'.
					'		</div>'.
					'	</div>'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Mail'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="email" name="admin_mail" required>'.
					'		</div>'.
					'	</div>'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Password'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="password" name="admin_password" required>'.
					'		</div>'.
					'	</div>'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Password repeat'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="password" name="admin_password2" required>'.
					'		</div>'.
					'	</div>'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Language'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<select class="form-control" name="language" required>'.$lang_options.
					'			</select>'.
					'		</div>'.
					'	</div>'.
					'	<p class="text-center"><input class="btn btn-primary" type="submit" name="start" value="submit"></p>'.
					'</form></div>';
			}else{
				$setup_result_message= '<p>Please delete the setup folder now!</p>';
			}
		}
			
	} 
	catch (PDOException $e) {
		error_log(date('Y-m-d H:i:s').' -- file: '.$e->getFile().' line:'.$e->getLine().' -- '.$e->getCode().' - '.$e->getMessage().PHP_EOL,3,ERROR_LOG);	
		die('Could not create database! Error Message: '.$e->getMessage());
	}
	
}elseif(isset($_POST['admin_user'])){
	
	try{
		$db = Db::getInstance();
		$login = new Login();		
		$user = $login->createUser($_POST['admin_user'], $_POST['admin_mail'], $_POST['admin_password'], $_POST['admin_password2'], $_POST['language'], false);
		$req2 = $db->prepare("INSERT INTO `members_rights` (`member_id`, `right_id`) VALUES (".$user->getId().", 1)");
		if($req2->execute()){
			$setup_result_message= '<h3>Installation complete!</h3><p>Please delete the setup folder now!</p>';
		}	
	}
	catch (Exception $e){
		error_log(date('Y-m-d H:i:s').' -- file: '.$e->getFile().' line:'.$e->getLine().' -- '.$e->getCode().' - '.$e->getMessage().PHP_EOL,3,ERROR_LOG);		
		die('Could not create account! Error Message: '.$e->getMessage());
	}
	catch (PDOException $e){
		error_log(date('Y-m-d H:i:s').' -- file: '.$e->getFile().' line:'.$e->getLine().' -- '.$e->getCode().' - '.$e->getMessage().PHP_EOL,3,ERROR_LOG);	
		die('Could not create account! Error Message: '.$e->getMessage());
	}
}else{
	
	$setup_result_message=  '<div style="max-width: 500px;" class="mx-auto">'.
			'<form name="start-setup" method="POST" action="">'.
			'	<p class="text-center"><input class="btn btn-primary" type="submit" name="start_setup" value="start setup"></p>'.
			'</form></div>';
	
}


$setup_result_message.= "<small>mysql version: ".$db->getAttribute(PDO::ATTR_SERVER_VERSION)."<br>php version: ".phpversion()."</small>";

require_once 'design.php';

?>