<?php
    require_once 'inc/routes.php';
    
    Routes::addRoute('', 'pages', 'home');
    Routes::addRoute('error.php', 'pages', 'notFound');
    Routes::addRoute('error_login.php', 'pages', 'notLoggedIn');
    
    Routes::addRoute('decks.php', 'deck', 'index');
    Routes::addRoute('decks/category.php', 'deck', 'category');
    Routes::addRoute('decks/deck.php', 'deck', 'deckpage');
    
    Routes::addRoute('memberlist.php', 'member', 'memberlist');
    Routes::addRoute('members/profil.php', 'member', 'profil');
    
    Routes::addRoute('member/dashboard.php', 'login', 'dashboard');
    Routes::addRoute('signup.php', 'login', 'signup');
    Routes::addRoute('signin.php', 'login', 'signin');
    Routes::addRoute('signout.php', 'login', 'signout');
    Routes::addRoute('lost_password.php', 'login', 'password');
    
    ROUTES::addRoute('admin', 'admin', 'dashboard');
    ROUTES::addRoute('admin/', 'admin', 'dashboard');
    ROUTES::addRoute('admin/settings.php', 'admin', 'settings');
    
    ROUTES::addRoute('admin/level.php', 'level', 'level');
    ROUTES::addRoute('admin/level/add.php', 'level', 'addLevel');
    ROUTES::addRoute('admin/level/edit.php', 'level', 'editLevel');
    
    ROUTES::addRoute('admin/members/list.php', 'member', 'adminMemberList');
    ROUTES::addRoute('admin/members/edit.php', 'member', 'adminEditMember');
    
    ROUTES::addRoute('admin/deck/upload.php', 'deck', 'deckUpload');
    ROUTES::addRoute('admin/deck/edit.php', 'deck', 'deckEdit');
    ROUTES::addRoute('admin/deck/list.php', 'deck', 'adminDeckList');
    
    ROUTES::addRoute('admin/categories.php', 'categories', 'categories');
    ROUTES::addRoute('admin/categories/add.php', 'categories', 'addCategory');
    ROUTES::addRoute('admin/categories/edit.php', 'categories', 'editCategory');
    ROUTES::addRoute('admin/subcategories/add.php', 'categories', 'addSubcategory');
    ROUTES::addRoute('admin/subcategories/edit.php', 'categories', 'editSubcategory');
?>