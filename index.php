<?php
require_once 'inc/constants.php';
require_once 'inc/dbconnect.php';
require_once 'routing.php';

// initialize database connection
if(!$db = Db::getInstance()) { die('DB not connected'); }

// process given uri
if(isset($_GET['uri'])) {
    echo $_GET['uri'];
    $controller = Routes::getController($_GET['uri']);
    $action = Routes::getAction($_GET['uri']);
    
    // if(!$controller OR !$action){ header('Location: error.php'); }
}else{
    $controller = 'pages';
    $action     = 'home';
}

if(file_exists('controllers/'.$controller.'.php')) {
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