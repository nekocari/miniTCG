<?php
/**
 * manage current user session data
 *
 * @author Cari
 *
 */
class Login {
    private $db, $user, $language;
    
    public function __construct() {
        $this->db		= Db::getInstance();
        if(isset($_SESSION['user_id'])){
            $this->user = Member::getById($_SESSION['user_id']);
            $this->language = $this->user->getSettings()->getValueByName('language');
            $this->updateMemberOnline();
        }
    }
    
    private function updateMemberOnline(){
    	if(($member_online = MemberOnline::getByMemberId($this->getUser()->getId())) instanceof MemberOnline){
    		// update if entry exists
    		$member_online->setPropValues(['ip'=>$_SERVER['REMOTE_ADDR']]);
    		$member_online->update();
    	}else{
    		// create new entry if not
    		$member_online = new MemberOnline();
    		$member_online->setPropValues(['member_id'=>$this->getUser()->getId(), 'ip'=>$_SERVER['REMOTE_ADDR']]);
    		$member_online->create();
    	}
    }
    
    
    /**
     * loign current user and store id into session
     * @param string $name
     * @param string $password
     * @throws ErrorException
     * @return boolean
     */
    public function login($name,$password) {    
        if(!$this->isloggedIn()){
            $member = Member::getByUniqueKey('name',$name);
            // check username / password
            if($member instanceof Member AND password_verify($password, $member->getPassword() ) ) {
            	// check status - allow only active member accounts
            	if($member->getStatus(false) == 'default'){
            		$_SESSION['user_id'] = $member->getId();
            		$_SESSION['user_name'] = $member->getName();
            		$_SESSION['user_lang'] = $member->getSettings()->getValueByName('lang');
            		$this->user = $member;
            		/*
            		// update db field - last login to now
            		$member->setPropValues(['perv_login_date'=>$member->getLoginDatePlain(), 'login_date'=>'CURRENT_TIMESTAMP', 'ip'=>$_SERVER['REMOTE_ADDR']]);
            		$member->update();
            		*/
            		// insert into members online table
            		$this->updateMemberOnline();
            		
            		
            		return true;
            	}else{
            		throw new ErrorException('account not active',1017);
            	}
            }else{
            	throw new ErrorException('password or name invalid',1016);
            }      
        }
        return false;
    }
    
    /**
     * log out current user
     * @return boolean
     */
    public function logout() {
        if($this->isloggedIn()){
            // delete member online entry
            if($mo = MemberOnline::getByMemberId($this->user->getId())){
                $mo->delete();
            }
            $this->user = null;
            // delete sesseion data
            session_unset();
            session_destroy();
            return true;
        }
    }
    
    /**
     * returns true if user is logged in or false if not
     * @return boolean
     */
    public function isloggedIn() {
    	if(!is_null($this->getUser() ) AND isset($_SESSION['user_name']) AND $_SESSION['user_name'] == $this->getUser()->getName() ){
            return true;
        }else{
            return false;
        }
    }
    
    public function isTeam() {
    	return Authorization::isTeam($this);
    }
    
    /**
     * get current users member data
     * @return Member|NULL
     */
    public function getUser() {
        return $this->user;
    }
    
    
    /**
     * registers a member as current user
     * @param Member $member
     */
    public function setUser(Member $member) {
        if($member instanceof Member){
            $this->user = $member;
        }
    }
    
    /**
     * get current user member data
     * @return Member|NULL
     */
    public function getUserId() {
        if($this->isloggedIn()){
            return $this->getUser()->getId();
        } else {
            return NULL;
        }
    }
    
    /**
     * checks if a user exists using name, url_name and mail
     * @param string $name 
     * @param string $mail 
     * @param string $url_name 
     * @return boolean
     */
    public function userExists($name, $mail){
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id FROM members WHERE name LIKE :name OR mail LIKE :mail');
        $req->execute(array(':name'=>$name, ':mail'=>$mail));
        if($req->rowCount() == 0){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * insert new member data to database
     * 
     * @param string $name the username
     * @param string $mail the e-mail
     * @param string $pw the password
     * @param string $pw the password repeated
     * @param string[] - day,month,year of birthday
     * @param string $lang language selected
     * 
     * @return boolean|Member
     */
    public function createUser($name, $mail, $pw1, $pw2, $lang = 'de') {
        
        // TODO: Create new User
        
    	// 1. user exists?
    	if($this->userExists($name, $mail)){
    		throw new ErrorException('Member NAME, or MAIL already taken',1011);
    		return false;
    	}
    	
    	// 2. check passwords
    	// empty?
    	if(trim($pw1) == ''){
    		throw new ErrorException('Password is empty',1012);
    		return false;
    	}
    	// match repeat?
    	if($pw1 != $pw2){
    		throw new ErrorException('Password does not match repetition', 1013);
    		return false;
    	}
    	
        // 3. add member entry
        $member = new Member();
        $member->setPropValues(['name'=>$name,'mail'=>$mail]);        
        $member->create();
        // set password (can't be done using default create or update...)
        $member->setPassword($pw1,$pw2);        
        // set language
        $member->getSettings()->setLang($lang);
        $member->getSettings()->update();
        
        // 4. create activation code and store in db
        $activation_code = new MemberActivationCode();
        $activation_code->setPropValues(['member_id'=>$member->getId(),'code'=>$this->getRandomActivationCode()]);
        if(is_null($activation_code->create())){
        	throw new ErrorException('unable create activation code entry! Please contact an administratior.',1014);
        }
        $activation_url = Routes::getUri('login_activation').'?code='.$activation_code->getCode().'&user='.$member->getId();
        
    	// 5. send mail with code
    	$subject = Setting::getByName('app_name')->getValue();
        $message = file_get_contents('app/views/'.$member->getLang().'/templates/mail_template_sign_up.php');
        $message_search = ['{{USERNAME}}','{{PASSWORD}}','{{ACTIVATIONURL}}'];
        $message_replace = [$name,$pw1,$activation_url];
        $message = str_replace($message_search, $message_replace, $message);
        
        // mail header
        $header[] = 'MIME-Version: 1.0';
        $header[] = 'Content-type: text/html; charset=UTF-8';
        $header[] = 'From: '.Setting::getByName('app_name')->getValue().' <'.Setting::getByName('app_mail')->getValue().'>';
        
        // send mail
        error_reporting(0);
        if(!mail($mail, $subject, $message, implode("\r\n", $header))){
    		throw new ErrorException('Unable to send activation code',1015);
    	}
    	error_reporting(-1);
    	return $member;
        
    }
    
    /**
     * checks code and activates account by deleting the code entry from db an sets user status to regular 'member'
     * @param string $code the activation code
     * @param int $member_id 
     * @return boolean
     */
    public function activateAccount($code,$member_id) {
    	$activation_code = MemberActivationCode::getByMemberId($member_id);
    	if($activation_code instanceof MemberActivationCode){
    		if($activation_code->getCode() == $code){
    			// delete code entry
	    		$activation_code->delete();
	    		// update user status
	    		$member = Member::getById($member_id);
	    		$member->setStatus('default');
	    		$member->update();
	    		// give starter cards
	    		$cards = Card::createRandomCards($member, intval(Setting::getByName('cards_startdeck_num')->getValue()));
	    		$cardnames = '';
	    		foreach($cards as $card){
	    			$cardnames.= $card->getName().' (#'.$card->getId().'), ';
	    		}
	    		$cardnames = substr($cardnames,0,-2);
	    		Tradelog::addEntry($member, 'starter_cards_log_text', $cardnames);
	    		return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Creates a random Code for account activation
     * @param int $length length of code string
     * @return string
     */
    public function getRandomActivationCode($length = 15) {
        $str = '';
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    
    public function delete($password){
    	if(password_verify($password, $this->getUser()->getPassword())){
    		return $this->getUser()->delete();
    	}else{
    		throw new Exception('password_invalid',9999);
    	}
    	return false;
    }
   
}
?>