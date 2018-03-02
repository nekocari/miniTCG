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
            header("Location: ".BASE_URI.Routes::getUri('signin'));
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
		    header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
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
		    header("Location: ".BASE_URI.Routes::getUri('member_dashboard'));
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
	
	
	/**
	 * Card Manager
	 */
	public function cardmanager() {
	    if(!isset($_SESSION['user'])){
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
	        if($updated == true){
	            $data['_success'][] = 'Sammlung aufgelöst. Die Karten sind nun wieder unter <i>NEW</i>';
	        }else{
	            $data['_error'][] = 'Sammlung konnte nicht aufgelöst werden.';
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
	
}
?>