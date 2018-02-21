<?php
/*
 * Manage Login
 */
class LoginController {
	
    /**
     * Member Dashboard 
     */
    public function dashboard() {
    	if(!isset($_SESSION['user'])){
    		header("Location: ".BASE_URI."signin.php");
    	}
      	Layout::render('login/dashboard.php');
    }
    
    /**
     * Sign in form
     */
	public function signin() {
		if(isset($_POST['username']) AND isset($_POST['password'])){
			require_once 'models/login.php';
			$login = new Login(Db::getInstance(), $_POST['username'],  $_POST['password']);
			$login_success= $login->login();
		}
		if(isset($_SESSION['user'])){
		    header("Location: ".BASE_URI."member/dashboard.php");
		}else{
	      	Layout::render('login/signin.php');
		}
    }

    /**
     * Sign out
     */
	public function signout() {
		if(isset($_SESSION['user'])){
		    require_once 'models/login.php';
		    if(Login::logout(Db::getInstance(),$_SESSION['user'])){
		        Layout::render('login/signout.php');
		    }
		}else{
		    header("Location: ".BASE_URI."member/dashboard.php");
		}
      	
    }

    /**
     * Sign up 
     */
    public function signup() {
    	if(isset($_SESSION['user'])){
			self::dashboard();
    	}else{
    	    require_once 'models/login.php';
    	    if(isset($_POST['username']) AND isset($_POST['mail']) AND isset($_POST['password']) AND isset($_POST['password_rep'])){
    	        $errors = array();
    	        if(trim($_POST['password']) == ''){
    	            $errors[] = 'Passwort muss gesetzt werden!';
    	        }
    	        if($_POST['password'] != $_POST['password_rep']){
    	            $errors[] = 'Passwörter stimmen nicht überein';
    	        }
		        if(Login::userExists($_POST['username'], $_POST['mail'])){
		            $errors[] = 'Benutzername oder E-Mailadresse bereits belegt';
		        }
		        
		        if(count($errors)==0 AND Login::newUser($_POST['username'], $_POST['password'] , $_POST['mail']) != false){
		            Layout::render('login/signup_successfull.php');
		        }else{
		            Layout::render('login/signup_error.php',['errors'=>$errors]);
		        }
		        
		    }else{
    	    	Layout::render('login/signup.php');
		    }
		}
	}
    
	/**
	 * account activation page
	 */
    public function activate() {
    	if(isset($_SESSION['user'])){
			self::dashboard();
		}else{
			require_once 'models/login.php';
			if(isset($_GET['code']) AND isset($_GET['user'])
				AND Login::activateAccount($_GET['code'], $_GET['user'])){
				$file_path = 'activation_true.php';
			}else{
		    	$file_path = 'activation_false.php';
			}
	    	Layout::render('login/'.$file_path);
		}
	}
    
	/**
	 * get password reset form
	 */
    public function password() {
    	if(isset($_SESSION['user'])){
			self::dashboard();
		}else{
	    	Layout::render('login/password.php');
		}
	}
	
}
?>