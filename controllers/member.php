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
    
}