<?php
require_once 'inc/constants.php';
require_once 'inc/dbconnect.php';

if(!$db = Db::getInstance()){ die('DB not connected'); }

if(isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
}else{
    $controller = 'pages';
    $action     = 'home';
}

if(file_exists('controllers/'.$controller.'.php')){
    require_once 'controllers/'.$controller.'.php';
    $classname = ucfirst($controller)."Controller";
    $controller = new $classname();
    if(method_exists($controller, $action)){
        $controller->{$action}();
    }else{
        die('action not found');
    }
}else{
    die('controller not found');
}

?>