<?php 

class MembersOnline {
    
    private $db;
    private $members = array();
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Fetches Data of all Members online
     * 
     * @return array(Member) - Returns an array of Member Objects
     */
    public function getOnlineMembers() {
        require_once PATH.'models/member.php';
        $query = "SELECT id, name FROM members_online JOIN members ON id = member ORDER BY name ASC";
        $req = $this->db->query($query);
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $member){
                $this->members[] = Member::getById($member->id);
            }
        }
        return $this->members;
    }
    
}

?>