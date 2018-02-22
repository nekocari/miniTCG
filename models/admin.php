<?php
/**
 * 
 * Represents a Admin
 * 
 * @author Cari
 *
 */
require_once 'login.php';

class Admin extends Login {
    
    private $rights;
    
    public function __construct($db, $user) {
        $this->db   = $db;
        $this->user = $user;
    }
    
    public function getRights() {
        if(!$this->rights AND $this->user != NULL){
            $req = $this->db->prepare('SELECT id, name FROM members_rights JOIN rights ON id = right_id WHERE member_id = :id');
            $req->execute(array(':id'=>$this->user->id));
            if($req->rowCount() > 0){
                foreach($req->fetchAll(PDO::FETCH_OBJ) as $right){
                    $this->rights[$right->id] = $right->name; 
                }
            }
        }
        return $this->rights;
    }
}