
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
    
    /**
     * Admin Memberlist
     */
    public function adminMemberList() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights())){
            require_once 'models/member.php';
            require_once 'helper/pagination.php';
            $members = Member::getAll('id', 'ASC');
            if(isset($_GET['pg'])){ 
                $currPage = $_GET['pg'];
            }else{
                $currPage = 1;
            }
            $pagination = new Pagination($members, 10, $currPage, Routes::getUri('admin_member_index'));
            $data['members'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml(); 
            
            Layout::render('admin/members/list.php',$data);
        }else{
            Layout::render('templates/error_rights.php');
        }
    }
    
    /**
     * Member edit for Admins
     */
    public function adminEditMember(){
        // TODO: add pw reset option
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights())){
            require_once 'models/member.php';
            if(isset($_POST['updateMemberdata'])){
                $updated_member = new Member($_POST['id'],$_POST['name'],$_POST['level'],$_POST['mail']);
                $return = $updated_member->store();
                if($return === true){
                    $data['_success'][] = 'Daten wurden gespeichert.';
                }else{
                    $data['_error'][] = 'Daten nicht aktualisiert. Datenbank meldet: '.$return;
                }
            }
            $data['memberdata'] = Member::getById($_GET['id']);
            if($data['memberdata']){
                Layout::render('admin/members/edit.php',$data);
            }else{
                Layout::render('admin/error.php',['errors'=>array('ID ist ungültig!')]);
            }
        }else{
            Layout::render('templates/error_rights.php');
        }
    }
    
}