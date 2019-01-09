<?php 
/**
 * helper class manage online members
 * 
 * @author Cari
 *
 */

class MembersOnline {
    
    private $db;
    private $members = array();
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    /**
     * Fetches Data of all Members online
     * 
     * @return array(Member) - Returns an array of Member Objects
     */
    public function getOnlineMembers() {
        require_once PATH.'models/member.php';
        $query = "DELETE FROM members_online WHERE date < (NOW() - INTERVAL 60 MINUTE)";
        $this->db->query($query);
        
        $query = "SELECT id, name FROM members_online JOIN members ON id = member ORDER BY name ASC";
        $req = $this->db->query($query);
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $member){
                $this->members[] = Member::getById($member->id);
            }
        }
        return $this->members;
    }
    
    /**
     * updates the timestamp of if viewer is a logged in user
     */
    public static function updateTime() {
        if(isset($_SESSION['user'])){
            $db = DB::getInstance();
            $req = $db->prepare('UPDATE members_online SET date = NOW() WHERE member = :id');
            $req->execute(array(':id'=>$_SESSION['user']->id));
            if($req->rowCount() == 0){
                $db->query('INSERT INTO members_online (member,date,ip) VALUES ('.$_SESSION['user']->id.',NOW(),\''.$_SERVER['REMOTE_ADDR'].'\') ON DUPLICATE KEY UPDATE ip = ip');
            }
        }
    }
    
}

?>