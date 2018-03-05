
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
        if(!isset($_GET['id'])){  header('Location: '.BASE_URI.'error.php'); }
            
        require_once 'models/member.php';
        require_once 'helper/pagination.php';
        
        // get user or relocate to error page
        $data['member'] = Member::getById(intval($_GET['id']));
        if($data['member'] == false){ header('Location: '.BASE_URI.'error.php'); }
        
        // current page for pagination
        if(isset($_GET['pg']) AND intval($_GET['pg'])>0 ){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // check category for partial
        if(isset($_GET['cat']) AND in_array($_GET['cat'], array('master','trade','keep','collect')) ){
            $cat = $_GET['cat'];
        }else{
            $cat = 'master';
        }
        // get elements for partial
        switch($cat){
            
            case 'master':
                $data['cat_elements'] = $data['member']->getMasteredDecks();
                break;
                
            case 'trade':
            case 'keep':
                $data['cat_elements'] = $data['member']->getCardsByStatus($cat);
                break;
                
            case 'collect':
                $data['cat_elements'] = $data['member']->getCardsByStatus($cat);
                foreach($data['cat_elements'] as $card){
                    $data['collections'][$card->getDeckId()][$card->getNumber()] = $card;
                    if(!isset($data['deckdata'][$card->getDeckId()])){
                        $data['deckdata'][$card->getDeckId()] = Carddeck::getById($card->getDeckId());
                    }
                }
                $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
                $data['cards_per_row'] = Setting::getByName('deckpage_cards_per_row')->getValue();
                $data['searchcard_html'] = Card::getSerachcardHtml();
                break;
        }
        
        $data['partial_uri'] = PATH.'views/member/profil/'.$cat.'.php';
        
        Layout::render('member/profil.php',$data);
          
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