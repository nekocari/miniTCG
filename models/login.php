<?php
class Login {
    private $name;
    private $password;
    private $db;
    protected $user;
    
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
            $req = $this->db->prepare('SELECT id, name, password as pw FROM members WHERE name LIKE :username');
            $req->execute(array(':username' => $this->name));
            
            if($req->rowCount() == 1) {
                $this->user = $req->fetch(PDO::FETCH_OBJ);
                if(password_verify($this->password, $this->user->pw)) {
                    $this->user->pw = null;
                    $_SESSION['user'] = $this->user;
                    // update db field - last login to now
                    $this->db->query('UPDATE members SET login_date = NOW(), ip = \''.$_SERVER['REMOTE_ADDR'].'\' WHERE id = '.$this->user->id);
                    return true;
                }
            }
        }
        return false;
    }
    
    public static function logout($db, $user) {
        if(isset($_SESSION['user'])){
            session_unset();
            session_destroy();
            // delete from member online table
            $db->query('DELETE FROM members_online WHERE member = '.$user->id);
            return true;
        }
    }
    
    /**
     * get current user data
     *
     * @return mixed|boolean
     */
    public function getUser() {
        if ($this->user){
            return $this->user;
        } else {
            return false;
        }
    }
    
    /**
     * set current user data
     *
     * @param mixed user data stored in session after login
     */
    public function setUser($user) {
        $this->user = $user;
    }
    
    public static function userExists($name, $mail){
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
     * @param string $pw the password
     * @param string $mail the e-mail
     * 
     * @return boolean|int
     */
    public static function newUser($name, $pw, $mail) {
        $db = Db::getInstance();
        
        $query = 'INSERT INTO members (name, password, mail) VALUES (:name, :password, :mail)';
        $req = $db->prepare($query);
        $req->execute(array(':name'=>$name, ':password'=>password_hash($pw,PASSWORD_BCRYPT), ':mail'=>$mail));
                
        $user_id = $db->lastInsertId();
        if($user_id) {
            // Insert Cards
            require_once 'models/card.php';
            require_once 'models/setting.php';
            $first_cards_num = Setting::getByName('cards_startdeck_num')->getValue();
            Card::createRandomCard($user_id,$first_cards_num,'Startdeck');
            // TODO activation code system
            // TODO send E-Mail            
            return $user_id;
        }else{
            return false;
        }
    }
    
    /**
     * TODO! 
     * activates account by deleting the activation code
     * 
     * @param string $code the activation code
     * @param int $user id of user
     * 
     * @return boolean
     */
    public static function activateAccount($code,$user) {
        $user = intval($user);
        $db = Db::getInstance();
        $req = $db->prepare("DELETE FROM activation WHERE member = :user AND code = :code");
        $req->execute(array(':user'=>$user,':code'=>$code));
        if($req->rowCount() == 1){
            return true;
        }else{
            return false;
        }
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
     * @param string $pw1   - new password string
     * @param string $pw2   - repetition to prevent unintended wrong inputs 
     * @throws Exception    - if password inputs do not match
     * 
     * @return boolean|string
     */
    public static function setPassword($pw1, $pw2) {
        try {
            if($pw1 == $pw2){
                $db = DB::getInstance();
                $req = $db->prepare('UPDATE members SET password = :password WHERE id = :id');
                $req->execute([':password'=>password_hash($pw1,PASSWORD_BCRYPT), ':id'=>$_SESSION['user']->id]);
                return true;
            }else{
                throw new Exception('Passwörter stimmen nicht überein');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
}
?>