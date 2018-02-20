<?php
    require_once 'inc/routes.php';
    
    Routes::addRoute('', 'pages', 'home');
    Routes::addRoute('error.php', 'pages', 'notFound');
    Routes::addRoute('error_login.php', 'pages', 'notLoggedIn');
    
    Routes::addRoute('memberlist.php', 'member', 'memberlist');
    Routes::addRoute('member/profil.php', 'member', 'profil');
    
    Routes::addRoute('member/dashboard.php', 'login', 'dashboard');
    Routes::addRoute('signup.php', 'login', 'signup');
    Routes::addRoute('signin.php', 'login', 'signin');
    Routes::addRoute('signout.php', 'login', 'signout');
    Routes::addRoute('lost_password.php', 'login', 'password');
?>