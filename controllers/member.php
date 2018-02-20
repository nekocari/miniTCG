<?php
/*
 * Controller for plain html pages
 */
class MemberController {
    
    public function memberlist() {
        global $db;
        require_once 'models/member.php';
        $members = Member::getAll($db, 'name', 'ASC');
        
        Layout::render('member/list.php',['members'=>$members]);
    }
    
    public function profil() {
        //if(!isset($_SESSION['user'])){ header('Location: '.BASE_URI.'login_error.php'); }
        if(isset($_GET['id'])){
            $id = intval($_GET['id']);
            global $db;
            require_once 'models/member.php';
            $member = Member::getById($id, $db);
            
            if($member != false){
                Layout::render('member/profil.php',['member'=>$member]);
            }else{
                header('Location: '.BASE_URI.'error.php');
            }
        }
    }
    
}