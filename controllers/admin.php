<?php
/**
 * Admin Controller
 */

require_once 'models/member.php';
require_once 'models/card.php';
require_once 'models/login.php';
require_once 'models/admin.php';
require_once 'models/right.php';
require_once 'models/setting.php';
require_once 'helper/pagination.php';

class AdminController {
    
    /**
     * Team Dashboard
     */
    public function dashboard() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!is_array($user_rights) OR count($user_rights) == 0 ){
            die(Layout::render('templates/error_rights.php'));
        }
        
        Layout::render('admin/dashboard.php');
          
    }
    
    /**
     * Settings
     */
    public function settings() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('EditSettings',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
        
        
        $data = array();
        
        // update form was sent
        if(isset($_POST['updateSettings']) AND isset($_POST['settings'])){
            
            // go throug all settings and store the values in database
            foreach($_POST['settings'] as $name => $value){
                                    
                $setting = Setting::getByName($name);
                $setting->setValue($value);     
                
                // create error message in case of failure
                if(!$setting->update()){
                    $data['_errors'][] = $name." not updated";
                }
                
            }
            
        }
        
        // if form was sent and no error message was created - create a success message
        if(isset($_POST['updateSettings']) AND !isset($data['_error'])){ 
            $data['_success'][] = SystemMessages::getSystemMessageText('admin_settings_updated'); 
        }
        
        // get all settings form database
        $data['settings'] = Setting::getAll(['name'=>'ASC']);
        
        Layout::render('admin/settings.php',$data);
          
    }
    
    
    /**
     * Memberlist
     */
    public function memberlist() {
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
                
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('ManageMembers',$user_rights) AND 
            !in_array('ManageRights',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
            
        $members = Member::getAll(['id'=>'ASC']);
        $currPage = 1;
        
        if(isset($_GET['pg'])){ 
            $currPage = $_GET['pg']; 
        }
        
        $pagination = new Pagination($members, 10, $currPage, Routes::getUri('admin_member_index'));
        
        $data['members'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        $data['currency_name'] = Setting::getByName('currency_name')->getValue();
        
        Layout::render('admin/members/list.php',$data);
        
    }
    
    /**
     * Member edit form
     */
    // TODO: add pw reset option
    public function editMember() {
        
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('ManageMembers',$user_rights) ){
                die(Layout::render('templates/error_rights.php'));
        }
            
        $memberdata = Member::getById($_GET['id']);
        
        if(isset($_POST['updateMemberdata'])){
            
            try {
                $memberdata->setName($_POST['Name']);
                $memberdata->setLevel($_POST['Level']);
                $memberdata->setMail($_POST['Mail']);
                $memberdata->setMoney($_POST['Money']);
                $memberdata->setInfoText($_POST['Text']);
                
                $return = $memberdata->store();
                
                $data['_success'][] = SystemMessages::getSystemMessageText('admin_edit_user_success');
            }
            catch(Exception $e){
                $data['_error'][] = SystemMessages::getSystemMessageText('admin_edit_user_failed').' - '.$e->getMessage();
            }
            
        }
       
        if(isset($_POST['deleteMemberdata'])){
            
            if($memberdata->delete()){
                $data['_success'][] = SystemMessages::getSystemMessageText('admin_delete_user_success');
                $memberdata = false;
            }else{                    
                $data['_error'][] = SystemMessages::getSystemMessageText('admin_delete_user_failed');
            }
            
        }
        
        if($memberdata){
            
            $data['memberdata'] = $memberdata->getEditableData('admin');
            
            Layout::render('admin/members/edit.php',$data);
            
        }else{
            
            $data['errors'] = array('ID NOT FOUND');
            
            Layout::render('admin/error.php',$data);
        }
    }
    
    /**
     * Member search form
     */
    public function searchMember() {
        
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }            
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('ManageMembers',$user_rights) AND
            !in_array('ManageRights',$user_rights)){
                die(Layout::render('templates/error_rights.php'));
        }
                
        if(!isset($_POST['search'])){
            
            Layout::render('admin/members/search.php');
            
        }else{
            
            $data = array();
            $data['members'] = Member::searchByName($_POST['search']);
            $data['pagination'] = '';
            
            Layout::render('admin/members/list.php',$data);
            
        }
        
    }
    
    
    
    /**
     * Member Gift Money
     */
    public function giftMoney(){
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('ManageMembers',$user_rights) ){
                die(Layout::render('templates/error_rights.php'));
        }

        // get current member data
        $data['member'] = $member = Member::getById($_GET['id']);
        if(!$member instanceof Member){
            header("Location: ".BASE_URI.Routes::getUri('not_found'));
        }
        
        // get currency name for later use in view and log text
        $data['currency_name'] = $currency_name = Setting::getByName('currency_name')->getValue();
        
        // if add money form as sent
        if(isset($_POST['addMoney']) and intval($_POST['addMoney']) and isset($_POST['text'])){
            
            // put together text for tradelog
            $log_text = SystemMessages::getSystemMessageText('admin_gift_money_log_text').' -> ';
            $log_text.= intval($_POST['addMoney']).' '.$currency_name.' ('.strip_tags($_POST['text']).')';
            
            // add money,update member data, send a pm, and set success message
            if($member->addMoney(intval($_POST['addMoney']),$log_text)){
                $member->update();
                
                // add a message for user
                Message::add(null, $member->getId(), $log_text);
                
                $data['_success'][] = SystemMessages::getSystemMessageText('admin_gift_money_success')." ".intval($_POST['addMoney']).' '.$currency_name;
            }else{
                // set error message
                $data['_error'][] = SystemMessages::getSystemMessageText('admin_gift_money_failed');
            }
        }
        
        Layout::render('admin/members/gift_money.php',$data);
        
    }
        
        
        
    /**
     * Member Gift Cards
     */
    public function giftCards(){
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('ManageMembers',$user_rights) ){
                die(Layout::render('templates/error_rights.php'));
        }
           
        // get current member data
        $data['member'] = $member = Member::getById($_GET['id']);
        
        // if add card form was sent
        if(isset($_POST['addCards']) and intval($_POST['addCards']) and isset($_POST['text'])){
            
            // put together text for tradelog
            $log_text = SystemMessages::getSystemMessageText('admin_gift_cards_log_text').' ('.strip_tags($_POST['text']).')';
            // create the set number of random cards
            $data['cards'] = Card::createRandomCard($member->getId(),$_POST['addCards'],$log_text);
            
            // if cards were created 
            if(is_array($data['cards']) AND count($data['cards']) > 0){
                
                // add all cardnames into a string
                $cardnames = '';
                foreach($data['cards'] as $card){
                    $cardnames.= $card->getName().", ";
                }
                $cardnames = substr($cardnames, 0, -2);
                
                // add a message for user
                Message::add(null, $member->getId(), $log_text." -> ".$cardnames);
                
                // set success message
                $data['_success'][] = SystemMessages::getSystemMessageText('admin_gift_cards_success').' '.$cardnames;
            
            // no cards were created
            }else{
                
                // set error message
                $data['_error'][] = SystemMessages::getSystemMessageText('admin_gift_cards_failed');
            
            }
        }
        
        Layout::render('admin/members/gift_cards.php',$data);
        
    }
    
    /**
     * manage user rights 
     */
    public function manageRights() {
        
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('ManageRights',$user_rights) ){
                die(Layout::render('templates/error_rights.php'));
        }
        
            
        // form to add right was submitted
        if(isset($_POST['add']) AND isset($_POST['right_id'])){
            if($member = Member::getById($_GET['id'])){
                $member->addRight($_POST['right_id']);
            }
        }
        
        // form to remove right was submitted
        if(isset($_POST['remove']) AND isset($_POST['right_id'])){
            if($member = Member::getById($_GET['id'])){
                $member->removeRight($_POST['right_id']);
            }
        }
        
        try {
            $member = Member::getById($_GET['id']);
            $member_rights = array();
            foreach($member->getRights() as $right){
                $member_rights[] = $right;
            }
            // get all possible rights
            $data['rights'] = Right::getAll();
            // get all data + rights current member already has
            $data['member'] = $member;
            $data['member_rights'] = $member_rights;
            
            Layout::render('admin/members/rights.php', $data);
            
        }
        catch(Exception $e) {
            $data['errors'][] = $e->getMessage();
            Layout::render('admin/error.php',$data);
        }           
        
    }
    
    public function importSql(){
                
        // if not logged in redirect to sign in form
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights)){
                die(Layout::render('templates/error_rights.php'));
        }
        
        $dir = PATH.'import/';
        $data = array();
        
        
        if(isset($_POST['delete'])){
            $file = $_POST['delete'];
            if(file_exists($dir.$file)){
                unlink($dir.$file);
                $data['_success'][] = '<p>Datei <b>'.$file.'</b> gelöscht</p>';
            }else{
                $data['_error'][] = '<p>Datei <b>'.$file.'</b> konnte nicht gelöscht werden. Datei nicht gefunden.</p>';
            }
        }
        
        if(isset($_POST['import'])){
            try{
                $file = $_POST['import'];
                $sql = file_get_contents($dir.$file);
                if($sql != false){
                    $db = Db::getInstance();
                    $req = $db->query($sql);
                    
                    $data['_success'][] = '<p>Datei <b>'.$file.'</b> eingelesen</p>';
                    $file = rename($dir.$file, $dir.'IMPORTED_'.$file);
                    
                    $req->closeCursor();
                }else{
                    $data['_error'][] = '<p>Datei <b>'.$file.'</b> nicht gefunden oder ist leer</p>';
                }
            }
            catch(PDOException $e){
                $data['_error'][] = '<p>Datei <i>'.$file.'</i> <b>nicht</b> eingelesen.<br>'.$e->getMessage().'</p>';
            }
        }
        
        
        // import sql
        foreach(scandir($dir) as $file){
            $fnarr = explode('.', $file);
            if(end($fnarr) == 'sql'){
                $data['files'][] = $file; 
            }
        }
        
        Layout::render('admin/import/list.php', $data);
        
    }

}
?>