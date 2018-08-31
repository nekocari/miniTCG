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
    
    Routes::addRoute('admin_member_index', 'admin/members/list.php', 'member', 'adminMemberList');
    Routes::addRoute('admin_member_edit', 'admin/members/edit.php', 'member', 'adminEditMember');
    Routes::addRoute('admin_member_gift_cards', 'admin/members/gift_cards.php', 'member', 'giftCards');
    
    Routes::addRoute('signup', 'signup.php', 'login', 'signup');
    Routes::addRoute('signin', 'signin.php', 'login', 'signin');
    Routes::addRoute('signout', 'signout.php', 'login', 'signout');
    Routes::addRoute('lost_password', 'lost_password.php', 'login', 'password');
    Routes::addRoute('member_dashboard', 'member/dashboard.php', 'login', 'dashboard');
    Routes::addRoute('member_cardmanager', 'member/cardmanager.php', 'login', 'cardmanager');
    Routes::addRoute('member_mastercards', 'member/mastercards.php', 'login', 'mastercards');
    Routes::addRoute('member_cardupdate', 'member/cardupdate.php', 'update', 'take');
    Routes::addRoute('edit_userdata', 'member/userdata.php', 'member', 'editUserdata');
    
    Routes::addRoute('tradelog_member', 'member/tradelog.php', 'tradelog', 'overview');
    Routes::addRoute('trades_recieved', 'member/trades_recieved.php', 'trade', 'recieved');
    Routes::addRoute('trades_sent', 'member/trades_sent.php', 'trade', 'sent');
    Routes::addRoute('trade', 'member/trade.php', 'trade', 'add');
    
    Routes::addRoute('admin_dashboard', 'admin/', 'admin', 'dashboard');
    Routes::addRoute('admin_settings', 'admin/settings.php', 'admin', 'settings');
    
    Routes::addRoute('news_index', 'admin/news/list.php','news', 'index');
    Routes::addRoute('news_add', 'admin/news/add.php','news', 'add');
    Routes::addRoute('news_edit', 'admin/news/edit.php','news', 'edit');
    
    Routes::addRoute('level_index', 'admin/level.php', 'level', 'level');
    Routes::addRoute('level_add', 'admin/level/add.php', 'level', 'addLevel');
    Routes::addRoute('level_edit', 'admin/level/edit.php', 'level', 'editLevel');
    
    Routes::addRoute('deck_update', 'admin/deck/update.php', 'update', 'updates');
    Routes::addRoute('deck_update_add', 'admin/deck/update/add.php', 'update', 'add');
    Routes::addRoute('deck_update_edit', 'admin/deck/update/edit.php', 'update', 'edit');
    Routes::addRoute('deck_upload', 'admin/deck/upload.php', 'deck', 'deckUpload');
    Routes::addRoute('deck_edit', 'admin/deck/edit.php', 'deck', 'deckEdit');
    Routes::addRoute('admin_deck_index', 'admin/deck/list.php', 'deck', 'adminDeckList');
    
    Routes::addRoute('category_index', 'admin/category.php', 'category', 'categories');
    Routes::addRoute('category_add', 'admin/category/add.php', 'category', 'addCategory');
    Routes::addRoute('category_edit', 'admin/category/edit.php', 'category', 'editCategory');
    Routes::addRoute('subcategory_add', 'admin/subcategory/add.php', 'category', 'addSubcategory');
    Routes::addRoute('subcategory_edit', 'admin/subcategorys/edit.php', 'category', 'editSubcategory');
?>