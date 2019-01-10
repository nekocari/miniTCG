<?php
/**
 * 
 * Represents a Admin
 * 
 * @author Cari
 *
 */
require_once 'member.php';

class Admin extends Member {
    
    private $rights = array();
    
    public function __construct($member_id) {
        
        $member = parent::getById($member_id);
        if($member){
            parent::__construct($member->getId(), $member->getName(), $member->getLevel(), $member->getMail(), $member->getJoinDate(), $member->getInfoTextPlain(), $member->getInfoText());
        }else{
            throw new Exception('ID ungültig');
        }
        
    }
    
    /**
     * get all rights assigned to current member as an array
     * 
     * @return array - Form: [right_id]=right_name
     */
    public function getRights() {
        
        if(!$this->rights){
            
            $req = $this->db->prepare('SELECT id, name FROM members_rights JOIN rights ON id = right_id WHERE member_id = :id');
            $req->execute(array(':id'=>$this->id));
            
            if($req->rowCount() > 0){
                
                foreach($req->fetchAll(PDO::FETCH_OBJ) as $right){
                    
                    $this->rights[$right->id] = $right->name; 
                    
                }
                
            }
            
        }
        
        return $this->rights;
    }
}