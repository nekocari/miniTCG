<?php
/*
 * Level Controller
 */

require_once 'models/admin.php';
require_once 'models/level.php';

class LevelController {
    
    /**
     * Level overview
     */
    public function level() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('ManageLevel',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
        
        $data['level'] = Level::getAll();
        
        Layout::render('admin/level/list.php',$data);
            
    }
    
    /**
     * Level add
     */
    public function addLevel() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('ManageLevel',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
            
        $data = array();
        
        if(isset($_POST['addLevel'])){
            
            try {
            $return = Level::add($_POST['level'], $_POST['name'], $_POST['cards']);
            header("Location: ".BASE_URI.Routes::getUri('level_index'));
            }
            catch(Exception $e){
                $error_text = ' - ';
                if($e instanceof PDOException){
                    if($e->getCode() == 23000){
                        $error_text.= SystemMessages::getSystemMessageText('duplicate_key');
                    }
                }
                $data['_error'][] = SystemMessages::getSystemMessageText('level_add_failed').$error_text;
            }
        }
        
        Layout::render('admin/level/add.php',$data);
            
        
    }
    
    /**
     * Level edit
     */
    public function editLevel() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }        
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND !in_array('ManageLevel',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }
            
        
        $data = array();
        $level = Level::getById($_GET['id']);
        
        
        if(isset($_POST['editLevel'])){
            
            try{
                $level->setPropValues(['level'=>$_POST['level'],'name'=>$_POST['name'],'cards'=>$_POST['cards']]);
                $level->update();
                
                $data['_success'][] = SystemMessages::getSystemMessageText('level_edit_success');
            }
            
            catch (Exception $e){
                $data['_error'][] = SystemMessages::getSystemMessageText('level_edit_failed');
                // todo: log exception
            }
            
        }
        
        $data['level'] = $level;
        Layout::render('admin/level/edit.php',$data);
         
    }
    
}
?>