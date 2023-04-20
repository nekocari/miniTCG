<?php
/*
 * Manage Login
 * 
 * @author NekoCari
 */

class LoginController extends AppController {
	
    /**
     * dashboard 
     */
    public function dashboard() {
        
        // if not logged in redirect to sign in form
        $this->redirectNotLoggedIn();
    	
      	$this->layout()->render('login/dashboard.php');
    }
    
    /**
     * Sign in form
     */
	public function signin() {
	    
	    $data = array();
	    
	    // login form was sent
	    if(isset($_POST['username']) AND isset($_POST['password'])){
			
			try {
				$this->login()->login($_POST['username'], $_POST['password']);
			}
			catch(Exception $e){
				die($e->getMessage());
				$this->layout()->addSystemMessage('error',$e->getCode());
			}
		}
		
		// if logged in redirect to dashboard
		if($this->login()->isloggedIn()){
		    header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		}
		
	    $this->layout()->render('login/signin.php',$data);
	    
    }

    /**
     * Sign out
     */
    public function signout() {
        
        // is logged in?
	    if($this->login()->isloggedIn()){
	        
	        // try to log out
		    if($this->login()->logout()){
		        
		        // on success
		        $this->layout()->render('login/signout.php');
		        
		    }else{
		        
		        // on failure redirect to dashboard
		        header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		        
		    }
		    
		// redirect to login form
		}else{
		    $this->redirectNotLoggedIn();
		}
      	
    }

    /**
     * Sign up 
     */
    public function signup() {
        
        // if logged in redirect to dashboard 
        if($this->login()->isloggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
    	}
    	
	    // check form data if exists
	    if(isset($_POST['username']) AND isset($_POST['mail']) AND isset($_POST['password']) AND isset($_POST['password_rep']) AND isset($_POST['lang'])){
	        try{
	        	$this->login()->createUser($_POST['username'], $_POST['mail'], $_POST['password'], $_POST['password_rep'], $_POST['lang'] );
	            $this->layout()->render('login/signup_successfull.php');
	        }
	        catch(Exception $e){;
	        	$this->layout()->addSystemMessage('error', $e->getCode());
                $this->layout()->render('login/signup.php');
            }
            
	    }else{
	        // display sign up form
	    	$this->layout()->render('login/signup.php');
	    }
		
	}
    
	/**
	 * account activation page
	 */
	public function activate() {
	    
	    // is logged in redirect to dashboard
	    if($this->login()->isloggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
	    }
	    
	    // process form data
		if(isset($_GET['code']) AND isset($_GET['user'])){
			try{
				if($this->login()->activateAccount($_GET['code'],$_GET['user'])){
					$this->layout()->render('login/activation_true.php');
				}else{
					$this->layout()->render('login/activation_false.php');
				}
			}
			catch(ErrorException $e){
				$this->layout()->addSystemMessage('error', '0',[],$e->getMessage());
				$this->layout()->render('login/activation_false.php');
			}
		}else{
			$this->redirectNotFound();
		}		
	}
    
	/**
	 * get password reset form
	 */
    public function password() {
        
        // if is logged in redirect to dashboard
        if($this->login()->isLoggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		}else{
		    
	    	$data = array();
	    	
		    if(isset($_POST['reset']) AND isset($_POST['mail'])){
		        // try setting a new random password and store it into $pw
		        try {
		            $member = Member::getByMail($_POST['mail']);
		            if($member instanceof Member){
    		            if($member->resetPassword()){
    		              $this->layout()->addSystemMessage('success','login_new_password_success');
    		            }else{
    		              $this->layout()->addSystemMessage('error','login_new_password_failed');
    		            }
		            }else{
		                throw new Exception('login_new_password_failed');
		            }
		        }
		        catch(Exception $e){
		            $sys_msg = SystemMessages::getSystemMessageText($e->getMessage());
		            if($sys_msg == 'TEXT MISSING'){
		                $sys_msg = SystemMessages::getSystemMessageText('unknowen_error').'<br>'.$e->getMessage();
		            }
		            $data['_error'][] = $sys_msg;
		        }
		    }
		    
		    $this->layout()->render('login/lost_password.php', $data);
	    	
		}
	}
	
	/**
	 * @deprecated moved to member controller!
	 *
	 * mastercards
	 */
	public function mastercards() {
	    $member_controller = new MemberController();
	    $member_controller->mastercards();
	}
	
	
	
	/**
	 * edit Userdata
	 */
	public function editUserdata(){
	    
	    $this->redirectNotLoggedIn();
	    
    	// get data of current member
	    $memberdata = $this->login()->getUser();
	    $membersettings = $memberdata->getSettings();
	    
	    // data
	    if(isset($_POST['updateMemberdata'])){
	    	try{
	    		$memberdata->setName($_POST['Name']);
	    		$memberdata->setMail($_POST['Mail']);
	    		$memberdata->setInfoText($_POST['Text']);
	    	}
	    	catch(Exception $e){
	    		switch($e->getCode()){
	    			case '8000':
	    				$this->layout()->addSystemMessage('error', '8000');
	    				break;
	    			default:
	    				$this->layout()->addSystemMessage('error', 'unknowen_error');
	    				break;
	    		}
	    	}
	    	try{
	    		if($memberdata->update()){
	    			$this->layout()->addSystemMessage('success', 'user_edit_data_success');
	    		}else{
	    			$this->layout()->addSystemMessage('error', 'user_edit_data_failed');
	    		}
	    	}
	    	catch(Exception $e){
	    		$this->layout()->addSystemMessage('error', '9999', array(), $e->getMessage());
	    	}
	    }
	    // Settings
	    if(isset($_POST['updateSettings'])){
	    	try{
	    		$membersettings->setLang($_POST['lang']);
	    		$membersettings->setTimezone($_POST['timezone']);
	    		$membersettings->update();
	    		$this->layout()->addSystemMessage('success', 'user_edit_settings_success');
	    	}
	    	catch(Exception $e){
	    		$this->layout()->addSystemMessage('error', 'unknown_error', array(), $e->getMessage());
	    	}
	    }
	    
	    // password change form was submitted
	    if(isset($_POST['changePassword'])){
	        
	        // try updating the password an set up message in case of success an failure
	    	if($this->login()->getUser()->updatePassword($_POST['password1'],$_POST['password2'])){
	    		$this->layout()->addSystemMessage('success', 'user_edit_pw_success');
	    	}else{
	    		$this->layout()->addSystemMessage('error', 'user_edit_pw_failed');
	        }
	    }
	    
	    // store the editable data into var
	    $data['memberdata'] = $memberdata->getEditableData();	
	    $data['member'] = $this->login()->getUser();
	    $this->layout()->render('login/edit_userdata.php',$data);
	    
	}
	
	/**
	 * Delete Account
	 */
	public function deleteAccount() {
	    
	    
	    // if form sent
		if(isset($_POST['delete']) AND isset($_POST['password'])){
	    	try{
	    		if($this->login()->delete($_POST['password'])){	    
	    			$this->login()->logout();
	    			$this->layout()->render('login/delete_account_success.php');
	    			return;
	    		}else{
	    			$this->layout()->addSystemMessage('error', 'delete_account_error');
	    		}
	    	}
	    	catch(PDOException $e){
	    		switch($e->getCode()){
	    			case 23000:
	    				$this->layout()->addSystemMessage('error', 'database_relations_exist_error');
	    				break;
	    		}
	    	}
	    	catch(Exception $e){
	    		switch($e->getCode()){
	    			case 9999:
	    				$this->layout()->addSystemMessage('error', 'password_invalid');
	    				break;
	    			default:
	    				$this->layout()->addSystemMessage('error', 'unknown_error',[],$e->getCode());
	    				error_log($e->getMessage().PHP_EOL,3,ERROR_LOG);
	    				break;
	    		}
	    	}   
	    	
		}
		
		$this->redirectNotLoggedIn();
		$this->layout()->render('login/delete_account.php');
	    
	}
	
}
?>