<?php
    require_once 'inc/routes.php';
    
    Routes::addRoute('homepage', '', 'pages', 'home');
    Routes::addRoute('not_found', 'error.php', 'pages', 'notFound');
    Routes::addRoute('login_error', 'error_login.php', 'pages', 'notLoggedIn');
    
    Routes::addRoute('deck_index', 'decks.php', 'deck', 'index');
    Routes::addRoute('deck_by_category', 'decks/category.php', 'deck', 'category');
    Routes::addRoute('deck_detail_page', 'decks/deck.php', 'deck', 'deckpage');
    
    Routes::addRoute('member_index', 'memberlist.php', 'member', 'memberlist');
    Routes::addRoute('member_profil', 'members/profil.php', 'member', 'profil');
    
    ROUTES::addRoute('admin_member_index', 'admin/members/list.php', 'member', 'adminMemberList');
    ROUTES::addRoute('admin_member_edit', 'admin/members/edit.php', 'member', 'adminEditMember');
    
    Routes::addRoute('member_dashboard', 'member/dashboard.php', 'login', 'dashboard');
    Routes::addRoute('member_cardmanager', 'member/cardmanager.php', 'login', 'cardmanager');
    Routes::addRoute('signup', 'signup.php', 'login', 'signup');
    Routes::addRoute('signin', 'signin.php', 'login', 'signin');
    Routes::addRoute('signout', 'signout.php', 'login', 'signout');
    Routes::addRoute('lost_password', 'lost_password.php', 'login', 'password');
    
    ROUTES::addRoute('admin_dashboard', 'admin/', 'admin', 'dashboard');
    ROUTES::addRoute('admin_settings', 'admin/settings.php', 'admin', 'settings');
    
    ROUTES::addRoute('level_index', 'admin/level.php', 'level', 'level');
    ROUTES::addRoute('level_add', 'admin/level/add.php', 'level', 'addLevel');
    ROUTES::addRoute('level_edit', 'admin/level/edit.php', 'level', 'editLevel');
    
    ROUTES::addRoute('deck_update', 'admin/deck/update.php', 'update', 'updates');
    ROUTES::addRoute('deck_update_add', 'admin/deck/update/add.php', 'update', 'add');
    ROUTES::addRoute('deck_update_edit', 'admin/deck/update/edit.php', 'update', 'edit');
    ROUTES::addRoute('deck_upload', 'admin/deck/upload.php', 'deck', 'deckUpload');
    ROUTES::addRoute('deck_edit', 'admin/deck/edit.php', 'deck', 'deckEdit');
    ROUTES::addRoute('admin_deck_index', 'admin/deck/list.php', 'deck', 'adminDeckList');
    
    ROUTES::addRoute('category_index', 'admin/category.php', 'category', 'categories');
    ROUTES::addRoute('category_add', 'admin/category/add.php', 'category', 'addCategory');
    ROUTES::addRoute('category_edit', 'admin/category/edit.php', 'category', 'editCategory');
    ROUTES::addRoute('subcategory_add', 'admin/subcategory/add.php', 'category', 'addSubcategory');
    ROUTES::addRoute('subcategory_edit', 'admin/subcategorys/edit.php', 'category', 'editSubcategory');
?>