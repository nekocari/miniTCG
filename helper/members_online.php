<?php 
/**
 * helper class manage online members
 * 
 * @author Cari
 *
 */

require_once PATH.'models/member.php';

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
        if($this->members == null){
            $query = "DELETE FROM members_online WHERE date < (NOW() - INTERVAL 5 MINUTE)";
            $this->db->query($query);
            
            $query = "SELECT m.* FROM members_online mo JOIN members m ON m.id = mo.member ORDER BY m.name ASC";
            $req = $this->db->query($query);
            if($req->rowCount() > 0){
                foreach($req->fetchAll(PDO::FETCH_CLASS,'Member') as $member){
                    $this->members[] = $member;
                }
            }
        }
        return $this->members;
    }
    
    /**
     * displays links of all members online in html format using a template
     * 
     * @return void
     */
    public function display($template_path='views/templates/member_online_temp.php') {
        $html = '';
        $members = $this->getOnlineMembers();
        $template = file_get_contents(PATH.$template_path);
        foreach($members as $member){
            $tpl = $template;
            $html.= str_replace(array('[NAME]','[URL]'), array($member->getName(), $member->getProfilLink()), $tpl);
        }
        echo $html;
    }
    
    /**
     * updates the timestamp if viewer is a logged in user
     */
    public static function updateTime() {
        if(isset($_SESSION['user'])){
            $db = DB::getInstance();
            $req = $db->prepare('UPDATE members_online SET date = NOW() WHERE member = :id');
            $req->execute(array(':id'=>Login::getUser()->getId()));
            if($req->rowCount() == 0){
                $db->query('INSERT INTO members_online (member,date,ip) VALUES ('.$_SESSION['user']->id.',NOW(),\''.$_SERVER['REMOTE_ADDR'].'\') ON DUPLICATE KEY UPDATE ip = ip');
            }
        }
    }
    
}

?>