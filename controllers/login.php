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
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
    	}
      	Layout::render('login/dashboard.php');
    }
    
    /**
     * Sign in form
     */
	public function signin() {
	    
		if(isset($_POST['username']) AND isset($_POST['password'])){
			$login = new Login(Db::getInstance(), $_POST['username'],  $_POST['password']);
			$login_success= $login->login();
		}
		
		if(Login::loggedIn()){
		    header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		}else{
	      	Layout::render('login/signin.php');
		}
    }

    /**
     * Sign out
     */
	public function signout() {
	    if(Login::loggedIn()){
		    if(Login::logout()){
		        Layout::render('login/signout.php');
		    }
		}else{
		    header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
		}
      	
    }

    /**
     * Sign up 
     */
    public function signup() {
        if(Login::loggedIn()){
			self::dashboard();
    	}else{
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
		        
		        if(count($errors)==0 AND ($new_user_id = Login::newUser($_POST['username'], $_POST['password'] , $_POST['mail'])) != false){
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
        if(Login::loggedIn()){
			self::dashboard();
		}else{
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
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
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
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
	    require_once 'helper/pagination.php';
	    require_once 'models/master.php';
	    
	    if(isset($_GET['pg']) AND intval($_GET['pg']) > 0){
	        $currPage = intval($_GET['pg']);
	    }else{
	        $currPage = 1;
	    }
	    if(isset($_GET['order'])){
	        $order = $_GET['order'];
	    }else{
	        $order = 'ASC';
	    }
	    if(isset($_GET['order_by'])){
	        $order_by = $_GET['order_by'];
	    }else{
	        $order_by = 'deckname';
	    }
	    $masters = Master::getMasterdByMember($_SESSION['user']->id,$order_by,$order);
	    
	    $pagination = new Pagination($masters, 20, $currPage, Routes::getController('member_mastercards'));
	    $data['mastered_decks'] = $pagination->getElements();
	    $data['pagination'] = $pagination->getPaginationHtml();
	    
	    Layout::render('login/mastercards.php',$data);
	}
	
	
	
	/**
	 * edit Userdata
	 */
	public function editUserdata(){
	    
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
    	    
	    $memberdata = Member::getById($_SESSION['user']->id);
	    
	    if(isset($_POST['updateMemberdata'])){
	        $memberdata->setName($_POST['Name']);
	        $memberdata->setMail($_POST['Mail']);
	        $memberdata->setInfoText($_POST['Text']);
	        
	        if(($return = $memberdata->store()) === true){
	            $data['_success'][] = 'Daten wurden gespeichert.';
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
	    
	    $data['memberdata'] = $memberdata->getEditableData();
	    unset($data['memberdata']['Level']);
	    
	    Layout::render('login/edit_userdata.php',$data);
	    
	}
	
	/**
	 * Delete Account
	 */
	public function deleteAccount() {
	    
	    if(!Login::loggedIn()){
	        header("Location: ".BASE_URI.Routes::getUri('signin'));
	    }
	    
	    if(!isset($_POST['delete']) OR !isset($_POST['password'])){
	        Layout::render('login/delete_account.php');
	    }else{
	        if(($result = Login::delete($_POST['password'])) === true){
    	        // delete the account
    	        Layout::render('login/delete_account_success.php');
    	    }else{
    	        $data['_error'][] = $result;
    	        Layout::render('login/delete_account.php',$data);
    	    }	        
	    }
	}
	
}
?>