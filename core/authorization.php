<?php

/**
 * define requirements in form of roles or rights and check if logged in user meets any of those requirements 
 * and is therefore authorized
 * 
 * @author Cari
 *
 */
class Authorization {
    private $login, $rights=array(), $roles=array();
    private static
        $accepted_roles = array('Admin','CardCreator'),
        $accepted_rights = array('ManageMembers','EditSettings','ManageCategories','ManageLevel','ManageNews','ManageUpdates','ManageRights');
    
    
    public function __construct($login) {
        $this->login = $login;
    }
    
    /**
     * specify requirements to match against
     * @param string $type [roles|rights]
     * @param string|string[] $requirement acceptes a string or an array of strings - @see self::accepted_rights
     * @throws ErrorException
     */
    public function setRequirements($type, $requirement){
        if($type != 'login' AND property_exists(__CLASS__, $type)){
            $match_prop_name = 'accepted_'.$type;
            if(is_string($requirement)){
                if(in_array($requirement, self::$$match_prop_name)){
                    $this->{$type}[] = $requirement;
                }else{
                    throw new ErrorException($requirement.' is not an accepted '.$type);
                }
            }elseif(is_array($requirement)){
                foreach($requirement as $req){
                    if(in_array($req, self::$$match_prop_name)){
                        $this->{$type}[] = $req;
                    }else{
                        throw new ErrorException($req.' is not an accepted '.$type);
                    }
                }
            }else{
                throw new ErrorException($type.' is not an accepted type');
            }
        }else{
            throw new ErrorException($type.' is an invalid type, only "roles" or "rights" are accepted');
        }
    }
    
    private function getLogin() {
        return $this->login;
    }
    
    private function getRoles() {
        return $this->roles;
    }
    
    private function getRights() {
        return $this->rights;
    }
    
    private function requirementsExist() {
        if((count($this->getRoles()) + count($this->getRights())) > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function getRequirements() {
        return array('roles'=>$this->getRoles(),'rights'=>$this->getRights());
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAuthorized() {
        // do requirements exist
        if($this->requirementsExist()){
            // is user logged in?
            if($this->getLogin()->isloggedin()){
                // check if user has any of the required roles
                if(count(array_intersect($this->getLogin()->getUser()->getRights(),$this->getRoles())) > 0 
                    OR count(array_intersect($this->getLogin()->getUser()->getRights(),$this->getRights())) > 0){
                    return true;
                }
            }
            return false;
        }
        return true;    
    }
    
    public static function isTeam($login) {
    	// is user logged in?
    	if($login->isloggedin()){
    		// check if user has any of the required roles
    		if(count(array_intersect($login->getUser()->getRights(),self::$accepted_roles)) > 0
    				OR count(array_intersect($login->getUser()->getRights(),self::$accepted_rights)) > 0){
    					return true;
    		}
    	}
    	return false;
    }
   
}
?>