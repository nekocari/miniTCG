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


if(isset($_POST['start_setup'])){
	try {
		// create tables
		$db = Db::getInstance();
		$mysql = file_get_contents(PATH.'setup/structure_v1_0beta.sql');
		$req = $db->prepare($mysql);
		if($req->execute()){
			$res = $req->fetchAll();
			$setup_result_message= '<div style="max-width: 500px;" class="mx-auto">'.
					'<h3>Database created!</h3>'.
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
					'			Admin Mail'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="email" name="admin_mail" required>'.
					'		</div>'.
					'	</div>'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Admin Password'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="password" name="admin_password" required>'.
					'		</div>'.
					'	</div>'.
					'	<div class="row justify-content-center my-2">'.
					'		<div class="col-12">'.
					'			Admin Password repeat'.
					'		</div>'.
					'		<div class="col-12">'.
					'			<input class="form-control" type="password" name="admin_password2" required>'.
					'		</div>'.
					'	</div>'.
					'	<input type="hidden" name="language" value="'.key(SUPPORTED_LANGUAGES).'">'.
					'	<p class="text-center"><input class="btn btn-primary" type="submit" name="start" value="submit"></p>'.
					'</form></div>';			
		}
	} 
	catch (PDOException $e) {
		error_log($e->getCode().' - '.$e->getMessage().PHP_EOL,3,ERROR_LOG);
		die('Could not create database! Error Message: '.$e->getMessage());
	}
	
}elseif(isset($_POST['admin_user'])){
	
	try{
		$db = Db::getInstance();
		$login = new Login();		
		$login->createUser($_POST['admin_user'], $_POST['admin_mail'], $_POST['admin_password'], $_POST['admin_password2'], $_POST['language'], false);
		$req2 = $db->prepare("INSERT INTO `members_rights` (`member_id`, `right_id`) VALUES (1, 1)");
		if($req2->execute()){
			$setup_result_message= '<h3>Installation complete!</h3><p>Please delete the setup folder now!</p>';
		}	
	}
	catch (Exception $e){		
		die('Could not create account! Error Message: '.$e->getMessage());
	}
	catch (PDOException $e){
		die('Could not create account! Error Message: '.$e->getMessage());
	}
}else{
	
	$setup_result_message=  '<div style="max-width: 500px;" class="mx-auto">'.
			'<form name="start-setup" method="POST" action="">'.
			'	<p class="text-center"><input class="btn btn-primary" type="submit" name="start_setup" value="start setup"></p>'.
			'</form></div>';
	
}


require_once 'design.php';

?>