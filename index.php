<?php
error_reporting(-1);
session_start();
require_once 'inc/constants.php';
require_once 'inc/dbconnect.php';
require_once 'routing.php';
require_once 'system_messages.php';
require_once 'helper/layout.php';
require_once 'helper/members_online.php';
require_once 'helper/Parsedown.php';

// initialize database connection
if(!$db = Db::getInstance()) { die('DB not connected'); }

// manage members online / active sessions
MembersOnline::updateTime();

// process given uri
if(isset($_GET['uri'])) {
    $identifier = ROUTES::getRouteKeyByUri($_GET['uri']);
    $controller = Routes::getController($identifier);
    $action = Routes::getAction($identifier);
    
    if(!$identifier OR!$controller OR !$action){ header('Location: '.BASE_URI.'error.php'); }
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