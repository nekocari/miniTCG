<?php
/*
 * Manage Login
 */
require_once 'models/login.php';

class LoginController {
	
    /**
     * dashboard 
     */
    public function dashboard() {
        // if not logged in redirect
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
			    $data['_error'][] = 'Benutzerdaten sind ungültig.';
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
            // redirect to dashboard
            header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
    	}
    	
	    // check form data if exists
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
	        
	        // if form data is ok and account was created successfully display success message
	        if(count($errors)==0 AND ($new_user_id = Login::newUser($_POST['username'], $_POST['password'] , $_POST['mail'])) != false){
	            Layout::render('login/signup_successfull.php');
	            
	        // if form data is NOT ok or account was NOT created display error message
	        }else{
	            Layout::render('login/signup_error.php',['errors'=>$errors]);
	        }
	    
	    // display sign up form
	    }else{
	    	Layout::render('login/signup.php');
	    }
		
	}
    
	/**
	 * account activation page
	 */
	public function activate() {
	    // is logged in?
	    if(Login::loggedIn()){
	        // redirect to dashboard
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
        if(Login::loggedIn()){
			self::dashboard();
		}else{
	    	Layout::render('login/password.php');
		}
	}
	
	
	/**
	 * card manager
	 */
	public function cardmanager() {
	    // if not logged in redirect
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
	    // models needed
	    require_once 'models/card.php';
	    require_once 'models/setting.php';
	    require_once 'models/carddeck.php';
	    
	    if(isset($_GET['status']) AND in_array($_GET['status'], Card::$accepted_status)){
	        $status = $_GET['status'];
	    }else{
	        $status = 'new';
	        if(isset($_GET['status'])){
	            $data['_error'][] = "Der übergebene Status <i>".$_GET['status']."</i> ist ungültig.";
	        }
	    }
	    
	    if(isset($_POST['changeCardStatus'])){
	        foreach($_POST['newStatus'] as $card_id => $new_status){
	            if($new_status != "" AND in_array($new_status, Card::$accepted_status) ){
	                $updated = Card::changeStatusById($card_id,$new_status, $_SESSION['user']->id);
	                if($updated == false){
	                    $card_name = Card::getById($card_id)->getName();
	                    $data['_error'][] = 'Karte <i>'.$card_name.'</i> nicht verschoben.';
	                }
	            }
	        }
	    }
	    
	    if(isset($_POST['dissolve'])){
	        $updated = Card::dissolveCollection($_POST['dissolve'], $_SESSION['user']->id);
	        if($updated === true){
	            $data['_success'][] = 'Sammlung aufgelöst. Die Karten sind nun wieder unter <i>NEW</i>';
	        }else{
	            $data['_error'][] = 'Sammlung konnte nicht aufgelöst werden.';
	        }
	    }
	    
	    if(isset($_POST['master'])){
	        $updated = Carddeck::master($_POST['master'], $_SESSION['user']->id);
	        if($updated === true){
	            $data['_success'][] = 'Sammlung gemastert!';
	        }else{
	            $data['_error'][] = 'Sammlung konnte nicht gemastert werden. '.$updated;
	        }
	    }
	    
	    $data['curr_status'] = $status;
	    $data['accepted_status'] = Card::$accepted_status;
	    $data['cards'] = Card::getMemberCardsByStatus($_SESSION['user']->id,$status);
	    
	    if($status != 'collect'){
	        Layout::render('login/cardmanager.php',$data);
	        
	    }else{
	        $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
	        $data['cards_per_row'] = Setting::getByName('deckpage_cards_per_row')->getValue();
	        $data['searchcard_html'] = Card::getSerachcardHtml();
	        $data['collections'] = array();
	        foreach($data['cards'] as $card){
	            $data['collections'][$card->getDeckId()][$card->getNumber()] = $card;
	            if(!isset($data['deckdata'][$card->getDeckId()])){
	                $data['deckdata'][$card->getDeckId()] = Carddeck::getById($card->getDeckId());
	            }
	        }
	        
	        Layout::render('login/collectmanager.php',$data);
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
	    // Models needed
	    require_once 'helper/pagination.php';
	    require_once 'models/master.php';
	    
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
	    $pagination = new Pagination($masters, 20, $curr_page, Routes::getController('member_mastercards'));
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
	    // Models needed
    	require_once 'models/member.php';
    	// get data of current member
	    $memberdata = Member::getById($_SESSION['user']->id);
	    
	    // if form was sent update object data
	    if(isset($_POST['updateMemberdata'])){
	        $memberdata->setName($_POST['Name']);
	        $memberdata->setMail($_POST['Mail']);
	        $memberdata->setInfoText($_POST['Text']);
	        
	        // if data was successfully stored return success message
	        if(($return = $memberdata->store()) === true){
	            $data['_success'][] = 'Daten wurden gespeichert.';
	        // if update was not successfull return error message
	        }else{
	            $data['_error'][] = 'Daten nicht aktualisiert. Datenbank meldet: '.$return;
	        }
	    }
	    
	    if(isset($_POST['changePassword'])){
	        if(($return = Login::setPassword($_POST['password1'],$_POST['password2'])) === true){
	            $data['_success'][] = 'Passwort wurde gespeichert.';
	        }else{
	            $data['_error'][] = 'Passwort nicht aktualisiert. Folgender Fehler trat auf: '.$return;
	        }
	    }
	    
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