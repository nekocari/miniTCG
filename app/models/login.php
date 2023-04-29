<?php

/*
 * @deprecated
 * 
 */

class Login {
    private $name;
    private $password;
    private $db;
    protected $user;
    private static $user_obj;
    
    public function __construct($db, $name, $password) {
        $this->db		= $db;
        $this->name 	= $name;
        $this->password	= $password;
    }
    /**
     * loign current user and store user data into session['user']
     * 
     * @return boolean|mixed
     */
    public function login() {    
        if(!isset($_SESSION['user'])){
            $req = $this->db->prepare('SELECT id, name, status, password as pw FROM members WHERE name LIKE :username');
            $req->execute(array(':username' => $this->name));
            
            if($req->rowCount() == 1) {
                $this->user = $req->fetch(PDO::FETCH_OBJ);
                if($this->user->status != 'default'){
                    throw new Exception('login_account_not_active');
                    return false;
                }
                if(password_verify($this->password, $this->user->pw)) {
                    $this->user->pw = null;
                    $_SESSION['user'] = $this->user;
                    // update db field - last login to now
                    $this->db->query('UPDATE members SET login_date = NOW(), ip = \''.$_SERVER['REMOTE_ADDR'].'\' WHERE id = '.$this->user->id);
                    return true;
                }else{
                    throw new Exception('login_sign_in_failed');
                }
            }else{
                throw new Exception('login_sign_in_failed');
            }
        }
        return false;
    }
    
    /**
     * log out current user
     * 
     * @return boolean
     */
    public static function logout() {
        if(isset($_SESSION['user'])){
            $db = DB::getInstance();
            // delete from member online table
            $db->query('DELETE FROM members_online WHERE member = '.intval($_SESSION['user']->id));
            // delete sesseion data
            session_unset();
            session_destroy();
            return true;
        }
    }
    
    /**
     * delete account
     */
    public static function delete($password) {
        try {
            // check if logged in
            if(self::loggedIn()){
                
                // does password match
                $db = DB::getInstance();
                $req = $db->prepare('SELECT password FROM members WHERE id = :id');
                $req->execute(array(':id' => $_SESSION['user']->id));
                if($req->rowCount() == 1) {
                    
                    if(password_verify($password, $req->fetchColumn())){
                        if($db->query('DELETE FROM members WHERE id = '.$_SESSION['user']->id)){
                            self::logout();
                            return true;
                        }
                    }else{
                        throw new Exception('Passwort ist nicht korrekt.');
                    }                    
                }else{
                    throw new Exception('Benutzerdaten nicht gefunden.');
                }
            }else{
                throw new Exception('Du bist nicht eingeloggt!');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * checkes if $_SESSION['user'] is set
     * 
     * @return boolean
     */
    public static function loggedIn() {
        if(isset($_SESSION['user'])){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * get current user data
     *
     * @return Member|NULL
     */
    public static function getUser() {
        if(is_null(self::$user_obj) AND self::loggedIn()){
            self::$user_obj = Member::getById($_SESSION['user']->id);
        }
        return self::$user_obj;
    }
    
    /**
     * set current user data
     *
     * @param mixed user data stored in session after login
     */
    public function setUser($user) {
        $this->user = $user;
    }
    
    /**
     * checks if a user exists using Name and Mail
     * 
     * @param string $name - name
     * @param string $mail - email adress
     * 
     * @return boolean
     */
    public static function userExists($name, $mail){
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id FROM members WHERE name LIKE :name OR mail LIKE :mail');
        $req->execute(array(':name'=>$name, ':mail'=>$mail));
        if($req->rowCount()){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * insert new member data to database
     * 
     * @param string $name the username
     * @param string $pw the password
     * @param string $mail the e-mail
     * 
     * @return boolean|int
     */
    public static function newUser($name, $pw, $mail) {
        
        $new_member_data = array(
            'name' => $name,
            'mail' => $mail
        );
        
        $new_member = new Member();
        $new_member->setPropValues($new_member_data);        
        $member_id = $new_member->create();
        $new_member->setPropValues(['id'=>$member_id]);
        $new_member->setPassword($pw);
        
        
        if($member_id != false){
            
            // create activation code and store it in db
            $activation_code = self::getRandomActivationCode(12);
            $member_code = new MemberActivationCode();
            $member_code->setPropValues(['member_id'=>$member_id,'code'=>$activation_code]);
            $member_code->create();
                    
            $startdeck_size = Setting::getByName('cards_startdeck_num')->getValue();
            $cards = Card::createRandomCard($member_id,$startdeck_size,'Startdeck');
            if(count($cards) < 1){
                throw new Exception('no cards created');
            }
            
            // get application mail and name from settings
            $app_name = Setting::getByName('app_name')->getValue();
            $app_mail = Setting::getByName('app_mail')->getValue();
            $base_uri = BASE_URI;
            if(substr($base_uri, strlen($base_uri)-1) != '/'){ $base_uri.= '/'; }
            $activation_url = $base_uri.Routes::getUri('login_activation').'?user='.$member_id.'&code='.$member_code->getCode();
            // set recipient
            $recipient  = $mail;
            // title
            $title = 'Deine Anmeldung bei '.$app_name;
            $message = file_get_contents(PATH.'inc/mail_template_sign_up.php');
            $message_search = ['{{MEMBERNAME}}','{{PASSWORD}}','{{ACTIVATION-URL}}','{{APPNAME}}'];
            $message_replace = [$name,$pw,$activation_url,$app_name];
            $message = str_replace($message_search, $message_replace, $message);
            
            // set mail header
            $header[] = 'MIME-Version: 1.0';
            $header[] = 'Content-type: text/html; charset=UTF-8';
            $header[] = 'From: '.$app_name.' <'.$app_mail.'>';
            
            // send E-Mail
            if(!mail($recipient, $title, $message, implode("\r\n", $header))){
                //echo $message;
                throw new Exception('Welcome mail not sent.');
            }
            
            return true;
        }
        
        return false;
    }
    
    
    /**
     * Creates a random Code for account activation
     * 
     * @param int $length length of code string
     * 
     * @return string
     */
    public static function getRandomActivationCode($length = 8) {
        $str = '';
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    
    /**
     * Changes password of user
     * 
     * @param string $pw1   - new password string
     * @param string $pw2   - repetition to prevent unintended wrong inputs 
     * @throws Exception    - if password inputs do not match
     * 
     * @return boolean|string
     */
    public static function setPassword($pw1, $pw2) {
        try {
            if($pw1 == $pw2 AND !empty($pw1)){
                $db = DB::getInstance();
                $req = $db->prepare('UPDATE members SET password = :password WHERE id = :id');
                $req->execute([':password'=>password_hash($pw1,PASSWORD_BCRYPT), ':id'=>$_SESSION['user']->id]);
                return true;
            }else{
                throw new Exception('Passwörter stimmen nicht überein, oder die Eingabe enthielt keine Daten.');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    
    /**
     * reset password
     *
     * @param string $mail the e-mail
     *
     * @return boolean|string - string contains new password
     */
    public static function resetPassword($mail) {
        $db = Db::getInstance();
        
        $member = Member::getByMail($mail);
        
        if($member instanceof Member){
            return $member->resetPassword();
        }else{
            return false;
        }
    }
    
    
}
?>