<?php 
/**
 * helper class for rendering the page content
 * Header and Footer will be added automaticaly
 * 
 * @author Cari
 * 
 */

require_once PATH.'helper/notification.php';
require_once PATH.'models/category.php';

class Layout {
    
    private static $headerTemplate = 'views/templates/header.php';
    private static $footerTemplate = 'views/templates/footer.php';
    private static $errorMsgTemplate = 'views/templates/message_error.php';
    private static $successMsgTemplate = 'views/templates/message_success.php';
    private static $infoMsgTemplate = 'views/templates/message_info.php';
    
    
    public static function render($view, $values = array()) {
        if(file_exists('views/'.$view)) {
            
            foreach($values as $key => $val) {
                $$key = $val;
            }
            // setze für das design benötigte variablen
            $tcg_categories = Category::getALL();
            
            // benachrichtigungenerstellen (sidebar)
            $tcg_notifications = new Notification();
            
            // members online
            $tcg_members_online = new MembersOnline();
            
            
            require_once self::$headerTemplate;
            if(isset($_error)){
                foreach($_error as $msg){
                    self::sysMessage($msg,'error');
                }
            }
            if(isset($_success)){
                foreach($_success as $msg){
                    self::sysMessage($msg,'success');
                }
            }
            if(isset($_info)){
                foreach($_info as $msg){
                    self::sysMessage($msg,'info');
                }
            }
            require_once 'views/'.$view;
            require_once self::$footerTemplate;
            
        }else{
            die('View not found!');
        }
    }
    
    /**
     * displays a system message using template files
     * 
     * @param string $type - allowed values [info|error|success]
     */
    public static function sysMessage($msg, $type='info') {
       // check if $type contains an allowed value and if not set 'info' as type
       $allowed_types = array('info','error','success');
       if(!in_array($type,$allowed_types)){
           $type = 'info';
       }
       
       switch($type){
           case 'error':
               $template = file_get_contents(self::$errorMsgTemplate);
               break;
           case 'success':
               $template = file_get_contents(self::$successMsgTemplate);
               break;
           case 'info':
               $template = file_get_contents(self::$infoMsgTemplate);
               break;
       }
       
       echo str_replace('[MESSAGE]',$msg,$template);
    }
    
}

?>