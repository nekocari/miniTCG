<?php
/*
 * Manage Login
 */
require_once PATH.'models/login.php';
require_once PATH.'models/card.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'helper/pagination.php';
require_once PATH.'models/master.php';
require_once PATH.'models/member.php';

class LoginController {
	
    /**
     * dashboard 
     */
    public function dashboard() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
    	}
    	
      	Layout::render('login/dashboard.php');
    }
    
    /**
     * Sign in form
     */
	public function signin() {
	    
	    $data = array();
	    
	    // login form was sent
		if(isset($_POST['username']) AND isset($_POST['password'])){
		
		    // try to login with post data
			$login = new Login(Db::getInstance(), $_POST['username'],  $_POST['password']);
			
			if(!$login->login()){
			    
			    $data['_error'][] = SystemMessages::getSystemMessageText('login_sign_in_failed');
			    
			}
		}
		
		// if logged in redirect to dashboard
		if(Login::loggedIn()){
		    header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		}
		
	    Layout::render('login/signin.php',$data);
	    
    }

    /**
     * Sign out
     */
    public function signout() {
        
        // is logged in?
	    if(Login::loggedIn()){
	        
	        // try to log out
		    if(Login::logout()){
		        
		        // on success
		        Layout::render('login/signout.php');
		        
		    }else{
		        
		        // on failure redirect to dashboard
		        header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		        
		    }
		    
		// redirect to login form
		}else{
		    
		    header("Location: ".BASE_URI.Routes::getUri('sign_in'));
		    
		}
      	
    }

    /**
     * Sign up 
     */
    public function signup() {
        
        // if logged in redirect to dashboard 
        if(Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
    	}
    	
	    // check form data if exists
	    if(isset($_POST['username']) AND isset($_POST['mail']) AND isset($_POST['password']) AND isset($_POST['password_rep'])){
	        try{
    	        $errors = array();
    	        
    	        if(trim($_POST['password']) == ''){
    	            $errors[] = SystemMessages::getSystemMessageText('login_sign_up_no_password');
    	        }
    	        
    	        if($_POST['password'] != $_POST['password_rep']){
    	            $errors[] = SystemMessages::getSystemMessageText('login_sign_up_password_not_matching');
    	        }
    	        
    	        if(Login::userExists($_POST['username'], $_POST['mail'])){
    	            $errors[] = SystemMessages::getSystemMessageText('login_sign_up_username_or_mail_taken');
    	        }
    	        // if form data is ok and account was created successfully display success message
    	        if(count($errors) == 0){
	            
	               Login::newUser($_POST['username'], $_POST['password'] , $_POST['mail']);
	               Layout::render('login/signup_successfull.php');
	                
                
    	        }else{
    	            // if form data is NOT ok 
    	            Layout::render('login/signup_error.php',['errors'=>$errors]);
    	        }
    	    }
            catch(Exception $e){
                $data['_error'][] = $e->getMessage();
                Layout::render('login/signup.php',$data);
            }
            
	    }else{
	        // display sign up form
	    	Layout::render('login/signup.php');
	    }
		
	}
    
	/**
	 * account activation page
	 */
	public function activate() {
	    
	    // is logged in redirect to dashboard
	    if(Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
	    }
	    
	    // process form data
		if(isset($_GET['code']) AND isset($_GET['user']) AND Login::activateAccount($_GET['code'], $_GET['user'])){
			Layout::render('login/activation_true.php');
			
		// no form data or data is invalid
		}else{
		    Layout::render('login/activation_false.php');
		}
	}
    
	/**
	 * get password reset form
	 */
    public function password() {
        
        // if is logged in redirect to dashboard
        if(Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
			
		}else{
		    
	    	$data = array();
	    	
		    if(isset($_POST['reset']) AND isset($_POST['mail'])){
		    		        
		        // try setting a new random password and store it into $pw
		        if(($pw = Login::resetPassword($_POST['mail'])) !== false){
		            // get application mail and name from settings
		            $app_name = Setting::getByName('app_name')->getValue();
		            $app_mail = Setting::getByName('app_mail')->getValue();
		            // get member name
		            $member_name = Member::getByMail($_POST['mail'])->getName();
		            
    		        // set recipient
    		        $recipient  = $_POST['mail'];
    		        // title
    		        $title = 'Neues Passwort für '.$app_name;
    		        // set message
    		        $message = '
                        <html>
                        <head>
                          <title>Neues Passwort für '.$app_name.'</title>
                        </head>
                        <body>
                          <p>Für deinen Account wurde ein neues Passwort angefordert. Das alte ist ab sofort ungültig.</p>
                          <p>Benutzername: '.$member_name.'<br>
                            neues Passwort: '.$pw.'</p>
                        </body>
                        </html> ';
    		        
    		        // set mail header
    		        $header[] = 'MIME-Version: 1.0';
    		        $header[] = 'Content-type: text/html; charset=UTF-8';
    		        $header[] = 'From: '.$app_name.' <'.$app_mail.'>';
    		        
    		        // send E-Mail
    		        try{
    		            mail($recipient, $title, $message, implode("\r\n", $header));
    		            //$data['_success'][] = $pw;
    		            $data['_success'][] = SystemMessages::getSystemMessageText('login_new_password_success');
    		        }
    		        catch(Exception $e){
    		            $data['_error'][] = SystemMessages::getSystemMessageText('unknowen_error').'<br>'.$e->getMessage();
    		        }
    		        
    		        
		        }else{
		            
		            $data['_error'][] = SystemMessages::getSystemMessageText('login_new_password_success');
		            
		        }
		        
		    }
		        
		    Layout::render('login/lost_password.php', $data);
	    	
		}
	}
	

	
	/**
	 * mastercards
	 */
	public function mastercards() {
	    
	    // if not logged in redirect
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
	    
	    
	    // Vars needed
	    $curr_page = 1;
	    $order = 'ASC';
	    $order_by = 'deckname';
	    
	    // process post vars if exist
	    if(isset($_GET['pg']) AND intval($_GET['pg']) > 0){
	        $curr_page = intval($_GET['pg']);
	    }
	    if(isset($_GET['order'])){
	        $order = $_GET['order'];
	    }
	    if(isset($_GET['order_by'])){
	        $order_by = $_GET['order_by'];
	    }
	    
	    // get masterd sets from database
	    $masters = Master::getMasterdByMember($_SESSION['user']->id,$order_by,$order);
	    
	    // pagination
	    $pagination = new Pagination($masters, 20, $curr_page, Routes::getUri('member_mastercards'));
	    
	    // set vars accessable in view
	    $data['mastered_decks'] = $pagination->getElements();
	    $data['pagination'] = $pagination->getPaginationHtml();
	    
	    // render page
	    Layout::render('login/mastercards.php',$data);
	}
	
	
	
	/**
	 * edit Userdata
	 */
	public function editUserdata(){
	    
	    // if not logged in redirect
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
	    
    	// get data of current member
	    $memberdata = Member::getById($_SESSION['user']->id);
	    
	    // if form was sent update object data
	    if(isset($_POST['updateMemberdata'])){
	        try{
    	        $memberdata->setName($_POST['Name']);
    	        $memberdata->setMail($_POST['Mail']);
    	        $memberdata->setInfoText($_POST['Text']);
	        }
	        catch(Exception $e){
	            switch($e->getCode()){
	                case '8000':
	                    $data['_info'][] = SystemMessages::getSystemMessageText($e->getCode());
	                    break;
	                default:
	                    $data['_error'][] = SystemMessages::getSystemMessageText('unknowen_error');
	                    break;
	            }
	        }
	        try{
	            // if data was successfully stored return success message
	            if($memberdata->store()){
	                $data['_success'][] = SystemMessages::getSystemMessageText('user_edit_data_success');
	                // if update was not successfull return error message
	            }else{
	                $data['_error'][] = SystemMessages::getSystemMessageText('user_edit_data_failed');
	            }
	        }
	        catch(Exception $e){
	            $data['_error'][] = SystemMessages::getSystemMessageText('9999').'<br>'.$e->getMessage();
	        }
	    }
	    
	    // password change form was submitted
	    if(isset($_POST['changePassword'])){
	        
	        // try updating the password an set up message in case of success an failure
	        if(($return = Login::setPassword($_POST['password1'],$_POST['password2'])) === true){
	            $data['_success'][] = SystemMessages::getSystemMessageText('user_edit_pw_success');
	        }else{
	            $data['_error'][] = SystemMessages::getSystemMessageText('user_edit_pw_failed').' '.$return;
	        }
	    }
	    
	    // store the editable data into var
	    $data['memberdata'] = $memberdata->getEditableData('user');	    
	    Layout::render('login/edit_userdata.php',$data);
	    
	}
	
	/**
	 * Delete Account
	 */
	public function deleteAccount() {
	    
	    // if not logged in redirect
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
	    
	    // if form was not sent
	    if(!isset($_POST['delete']) OR !isset($_POST['password'])){
	        Layout::render('login/delete_account.php');
	        
	    // form was sent
	    }else{
	        
	        // try to delete the account 
	        if(($result = Login::delete($_POST['password'])) === true){
    	        // on success display message
    	        Layout::render('login/delete_account_success.php');
    	    }else{
    	        // display error 
    	        $data['_error'][] = $result;
    	        Layout::render('login/delete_account.php',$data);
    	    }	
    	    
	    }
	}
	
}
?>