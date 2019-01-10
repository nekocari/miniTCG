<?php
/**
 * Admin Controller
 */

require_once 'models/member.php';
require_once 'models/card.php';
require_once 'models/login.php';
require_once 'models/admin.php';
require_once 'models/setting.php';

class AdminController {
    
    /**
     * Team Dashboard
     */
    public function dashboard() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        // check if user has any rights
        if($admin->getRights() != NULL){
            
            Layout::render('admin/dashboard.php');
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * Settings
     */
    public function settings() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        // check if user has any rights
        if(in_array('Admin',$admin->getRights())){
            
            $data = array();
            
            // update form was sent
            if(isset($_POST['updateSettings']) AND isset($_POST['settings'])){
                
                // go throug all settings and store the values in database
                foreach($_POST['settings'] as $name => $value){
                    
                    $setting = new Setting($name, $value);
                    $return = $setting->store();
                    
                    // create error message in case of failure
                    if($return !== true){
                        $data['_errors'][] = $return;
                    }
                    
                }
                
            }
            
            // if form was sent and no error message was created - create a success message
            if(isset($_POST['updateSettings']) AND !isset($data['_error'])){ 
                $data['_success'] = array('Settings wurden aktualisiert.'); 
            }
            
            // get all settings form database
            $data['settings'] = Setting::getAll();
            
            Layout::render('admin/settings.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    
    /**
     * Memberlist
     */
    public function memberlist() {
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
            OR in_array('manage_member',$admin->getRights())){
            
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
     * Member edit form
     */
    // TODO: add pw reset option
    public function editMember() {
        
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
            OR in_array('manage_member',$admin->getRights())){
            
            $memberdata = Member::getById($_GET['id']);
            
            if(isset($_POST['updateMemberdata'])){
                
                $memberdata->setName($_POST['Name']);
                $memberdata->setLevel($_POST['Level']);
                $memberdata->setMail($_POST['Mail']);
                $memberdata->setInfoText($_POST['Text']);
                
                $return = $memberdata->store();
                
                if($return === true){
                    $data['_success'][] = 'Daten wurden gespeichert.';
                }else{
                    $data['_error'][] = 'Daten nicht aktualisiert. Datenbank meldet: '.$return;
                }
                
            }
           
            if(isset($_POST['deleteMemberdata'])){
                
                if($memberdata->delete()){
                    $data['_success'][] = 'Die Mitgliedsdaten wurden komplett gelöscht.';
                    $memberdata = false;
                }else{                    
                    $data['_error'][] = 'Es wurden keine Daten gelöscht.';
                }
                
            }
            
            if($memberdata){
                
                $data['memberdata'] = $memberdata->getEditableData();
                
                Layout::render('admin/members/edit.php',$data);
                
            }else{
                
                $data['errors'] = array('ID ist ungültig!');
                
                Layout::render('admin/error.php',$data);
                
            }
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * Member search form
     */
    public function searchMember() {
        
        if(Login::loggedIn()){
            
            $admin = new Admin($_SESSION['user']->id);
            
            if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
                OR in_array('manage_member',$admin->getRights())){
                        
                if(!isset($_POST['search'])){
                    
                    Layout::render('admin/members/search.php');
                    
                }else{
                    
                    $data = array();
                    $data['members'] = Member::searchByName($_POST['search']);
                    $data['pagination'] = '';
                    
                    Layout::render('admin/members/list.php',$data);
                    
                }
                
            }else{
                
                Layout::render('templates/error_rights.php');
                
            }
            
        }else{
            
            header("Location: ".BASE_URI.Routes::getUri('signin'));
            
        }
    }
   
    
    /**
     * Member Gift Cards
     */
    public function giftCards(){
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        // check if user rights matches one of the conditions
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
            OR in_array('manage_member',$admin->getRights())){
            
            // get current member data
            $data['member'] = $member = Member::getById($_GET['id']);
            
            // if add card form was sent
            if(isset($_POST['addCards']) and intval($_POST['addCards']) and isset($_POST['text'])){
                
                // create the set number of random cards
                $data['cards'] = Card::createRandomCard($member->getId(),$_POST['addCards'],'manuelle GUTSCHRIFT ('.strip_tags($_POST['text'].')'));
                
                // if cards were created 
                if(count($data['cards']) > 0){
                    
                    // add all cardnames into a string
                    $cardnames = '';
                    foreach($data['cards'] as $card){
                        $cardnames.= $card->getName().", ";
                    }
                    $cardnames = substr($cardnames, 0, -2);
                    
                    // set success message
                    $data['_success'][] = 'Gutschrift erfolgt: '.$cardnames;
                
                // no cards were created
                }else{
                    
                    // set error message
                    $data['_error'][] = 'Gutschrift fehlgeschlagen.';
                
                }
            }
            
            Layout::render('admin/members/gift_cards.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * manage user rights -> TODO
     */
    public function manage_rights() {
        // TODO ---> in progress
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        // check if user rights matches one of the conditions
        if(in_array('Admin',$admin->getRights()) OR in_array('manage_member_rights',$admin->getRights())){
                
            // get current member data
            $data['member'] = $member = Member::getById($_GET['id']);
            
            // form to add right was submitted
            if(isset($_POST['add']) AND isset($_POST['right_id'])){
                
            }
            
            // form to remove right was submitted
            if(isset($_POST['remove']) AND isset($_POST['right_id'])){
                
            }
            
            // get all rights current member already has
            $member_as_admin = new Admin($member->getId());
            $data['rights'] = $member_as_admin->getRights();
            
            
            
        }
        
    }

}
?>