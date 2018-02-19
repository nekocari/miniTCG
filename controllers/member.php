<?php
/*
 * Controller for plain html pages
 */
class MemberController {
    
    public function memberlist() {
        global $db;
        require_once 'models/member.php';
        $this->members = Member::getAll($db, 'name', 'ASC');
        require_once 'views/members/list.php';
    }
    
}