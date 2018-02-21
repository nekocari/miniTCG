<?php 

class MembersOnline {
    
    private $db;
    private $members = array();
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    
    public function getOnlineMembers() {
        $query = "SELECT id, name FROM members_online JOIN members ON id = member ORDER BY name ASC";
        $req = $this->db->query($query);
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $member){
                $this->members[] = $member;
            }
        }
        return $this->members;
    }
    
}

?>