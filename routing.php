<?php
    require_once 'inc/routes.php';
    
    Routes::addRoute('', 'pages', 'home');
    Routes::addRoute('error.php', 'pages', 'notfound');
    Routes::addRoute('memberlist.php', 'member', 'memberlist');
?>