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
        require_once 'models/level.php';
        $data['members'] = Member::getGrouped('level');
        $data['level'] = Level::getAll();
        
        Layout::render('member/list.php',$data);
    }
    
    /**
     * get a member profil using get id
     */
    public function profil() {
        if(!isset($_SESSION['user'])){ header('Location: '.BASE_URI.'error_login.php'); }
        if(isset($_GET['id'])){
            $id = intval($_GET['id']);
            require_once 'models/member.php';
            $data['member'] = Member::getById($id);
            
            if($data['member'] != false){
                Layout::render('member/profil.php',$data);
            }else{
                header('Location: '.BASE_URI.'error.php');
            }
        }
    }
    
}