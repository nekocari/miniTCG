<?php 

class Layout {
    
    private static $headerTemplate = 'views/templates/header.php';
    private static $footerTemplate = 'views/templates/footer.php';
    
    
    public static function render($view, $values = array()) {
        foreach($values as $key => $val) {
            $$key = $val;
        }
        if(file_exists('views/'.$view)) {
            require_once self::$headerTemplate;
            require_once 'views/'.$view;
            require_once self::$footerTemplate;
        }else{
            die('View not found!');
        }
    }
    
}

?>