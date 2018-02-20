<?php
/*
 * Controller for member related pages
 */
class MemberController {
    
    /**
     * get the memberlist
     */
    public function memberlist() {
        require_once 'models/member.php';
        $members = Member::getAll('name', 'ASC');
        
        Layout::render('member/list.php',['members'=>$members]);
    }
    
    /**
     * get a member profil using get id
     */
    public function profil() {
        if(!isset($_SESSION['user'])){ header('Location: '.BASE_URI.'error_login.php'); }
        if(isset($_GET['id'])){
            $id = intval($_GET['id']);
            require_once 'models/member.php';
            $member = Member::getById($id);
            
            if($member != false){
                Layout::render('member/profil.php',['member'=>$member]);
            }else{
                header('Location: '.BASE_URI.'error.php');
            }
        }
    }
    
}