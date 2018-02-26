<?php
/*
 * Admin Controller
 */
class AdminController {
    
    /**
     * Team Dashboard
     */
    public function dashboard() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
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
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if($admin->getRights() != NULL){
            
            require_once PATH.'models/setting.php';
            $data = array();
            if(isset($_POST['updateSettings']) AND isset($_POST['settings'])){
                foreach($_POST['settings'] as $name => $value){
                    $setting = new Setting($name, $value);
                    $return = $setting->store();
                    if($return !== true){
                        $data['_errors'][] = $return;
                    }
                }
            }
            if(isset($_POST['updateSettings']) AND !isset($data['_error'])){ 
                $data['_success'] = array('Settings wurden aktualisiert.'); 
            }
            $data['settings'] = Setting::getAll();
            Layout::render('admin/settings.php',$data);
        }else{
            Layout::render('templates/error_rights.php');
        }
    }

}
?>