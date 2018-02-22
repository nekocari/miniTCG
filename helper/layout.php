<?php 

class Layout {
    
    private static $headerTemplate = 'views/templates/header.php';
    private static $footerTemplate = 'views/templates/footer.php';
    private static $errorMsgTemplate = 'views/templates/message_error.php';
    private static $successMsgTemplate = 'views/templates/message_success.php';
    
    
    public static function render($view, $values = array()) {
        foreach($values as $key => $val) {
            $$key = $val;
        }
        if(file_exists('views/'.$view)) {
            require_once self::$headerTemplate;
            if(isset($_error)){
                $template = file_get_contents(self::$errorMsgTemplate);
                foreach($_error as $msg){
                    echo str_replace('[MESSAGE]',$msg,$template);
                }
            }
            if(isset($_success)){
                $template = file_get_contents(self::$successMsgTemplate);
                foreach($_success as $msg){
                    echo str_replace('[MESSAGE]',$msg,$template);
                }
            }
            require_once 'views/'.$view;
            require_once self::$footerTemplate;
        }else{
            die('View not found!');
        }
    }
    
}

?>