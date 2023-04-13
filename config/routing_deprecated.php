<?php
    /* do not change this line */
    require_once 'core/routes.php';
    /*
     * add your own routes here! 
     * =========================
     * 
     * How to add a new route:
     * 
     * RoutesDb::addRoute('IDENTIFIER','URL','controller','action','method');
     * IDENTIFIER   -> a unique key for the route, which can be used to create links within the app to the right url
     * URL          -> the url of your new page in the browser
     * CONTROLLER   -> for simple html pages you can use the pages controller and add your own action (a method) to it.
     * ACTION       -> method within the controller that will be executed; where your page is put together using the $this->layout()->render() method
     * METHOD		-> empty = get only, post for form processing etc, get|post enables both
     */
    
    
    RoutesDb::addRoute('faq', 'faq.php', 'Pages', 'faq'); // empty template for you to fill
    RoutesDb::addRoute('imprint', 'imprint.php', 'Pages', 'imprint'); // empty template for you to fill
    
    
    /**
     * DO NOT EDIT BELOW THIS LINE - unless you know what you are doing!
     *********************************************************************
     */
    
    
    RoutesDb::addRoute('homepage', '', 'Pages', 'home');
    RoutesDb::addRoute('not_found', 'error.php', 'Pages', 'notFound');
    RoutesDb::addRoute('login_error', 'error_login.php', 'Pages', 'notLoggedIn');
    
    RoutesDb::addRoute('deck_index', 'decks.php', 'Deck', 'index');
    RoutesDb::addRoute('deck_by_category', 'decks/category.php', 'Deck', 'category');
    RoutesDb::addRoute('deck_detail_page', 'decks/deck.php', 'Deck', 'deckpage');
    
    RoutesDb::addRoute('member_index', 'memberlist.php', 'Member', 'memberlist');
    RoutesDb::addRoute('member_profil', 'members/profil.php', 'Member', 'profil');
    
    RoutesDb::addRoute('admin_dashboard', 'admin/', 'Admin', 'dashboard');
    RoutesDb::addRoute('admin_settings', 'admin/settings.php', 'Admin', 'settings','get|post');
    RoutesDb::addRoute('admin_member_index', 'admin/members/list.php', 'Admin', 'memberlist');
    RoutesDb::addRoute('admin_member_search', 'admin/members/search.php', 'Admin', 'searchMember','get|post');
    RoutesDb::addRoute('admin_member_edit', 'admin/members/edit.php', 'Admin', 'editMember','get|post');
    RoutesDb::addRoute('admin_member_manage_rights', 'admin/members/manage_rights.php', 'Admin', 'manageRights','get|post');
    RoutesDb::addRoute('admin_member_reset_password', 'admin/members/reset_password.php', 'Admin', 'resetPassword');
    RoutesDb::addRoute('admin_member_gift_cards', 'admin/members/gift_cards.php', 'Admin', 'giftCards','get|post');
    RoutesDb::addRoute('admin_member_gift_money', 'admin/members/gift_money.php', 'Admin', 'giftMoney','get|post');
    RoutesDb::addRoute('admin_deck_index', 'admin/deck/list.php', 'Deck', 'adminDeckList');
    RoutesDb::addRoute('admin_sql_import', 'admin/sql_import.php', 'Admin', 'importSql');
    
    RoutesDb::addRoute('signup', 'signup.php', 'Login', 'signup','get|post');
    RoutesDb::addRoute('signin', 'signin.php', 'Login', 'signin','get|post');
    RoutesDb::addRoute('signout', 'signout.php', 'Login', 'signout');
    RoutesDb::addRoute('login_activation', 'activation.php', 'Login', 'activate');
    RoutesDb::addRoute('lost_password', 'lost_password.php', 'Login', 'password');
    RoutesDb::addRoute('edit_userdata', 'member/userdata.php', 'Login', 'editUserdata');
    RoutesDb::addRoute('delete_account', 'member/delete_account.php', 'Login', 'deleteAccount');
    
    RoutesDb::addRoute('member_dashboard', 'member/dashboard.php', 'Login', 'dashboard');
    RoutesDb::addRoute('member_cardmanager', 'member/cardmanager.php', 'Cardmanager', 'cardmanager','get|post');
    RoutesDb::addRoute('member_mastercards', 'member/mastercards.php', 'Member', 'mastercards');
    RoutesDb::addRoute('member_cardupdate', 'member/cardupdate.php', 'Update', 'take');
    
    RoutesDb::addRoute('messages_recieved', 'member/inbox.php', 'Message', 'received');
    RoutesDb::addRoute('messages_sent', 'member/outbox.php', 'Message', 'sent');
    RoutesDb::addRoute('messages_write', 'member/new_message.php', 'Message', 'write');
    
    RoutesDb::addRoute('card_search', 'cardsearch.php', 'Card', 'search','get|post');
    
    RoutesDb::addRoute('shop', 'cardshop.php', 'shop', 'Shop');
    
    RoutesDb::addRoute('game', 'games.php', 'Game', 'index');
    RoutesDb::addRoute('game_lucky_number', 'games/lucky_number.php', 'Game', 'luckyNumber');
    RoutesDb::addRoute('game_head_or_tail', 'games/head_or_tail.php', 'Game', 'headOrTail');
    RoutesDb::addRoute('game_trade_in', 'games/trade_in.php', 'Game', 'tradeIn');
    
    RoutesDb::addRoute('tradelog_member', 'member/tradelog.php', 'Tradelog', 'overview');
    RoutesDb::addRoute('trades_recieved', 'member/trades_recieved.php', 'Trade', 'recieved');
    RoutesDb::addRoute('trades_sent', 'member/trades_sent.php', 'Trade', 'sent');
    RoutesDb::addRoute('trade', 'member/trade.php', 'Trade', 'add','get|post');
    
    RoutesDb::addRoute('news_index', 'admin/news/list.php','News', 'index');
    RoutesDb::addRoute('news_add', 'admin/news/add.php','News', 'add','get|post');
    RoutesDb::addRoute('news_edit', 'admin/news/edit.php','News', 'edit','get|post');
    RoutesDb::addRoute('news_link_update', 'admin/news/update.php','News', 'linkedUpdate','get|post');
    
    RoutesDb::addRoute('level_index', 'admin/level.php', 'Level', 'level');
    RoutesDb::addRoute('level_add', 'admin/level/add.php', 'Level', 'addLevel');
    RoutesDb::addRoute('level_edit', 'admin/level/edit.php', 'Level', 'editLevel');
    
    RoutesDb::addRoute('deck_update', 'admin/deck/update.php', 'Update', 'updates','get|post');
    RoutesDb::addRoute('deck_update_add', 'admin/deck/update/add.php', 'Update', 'add','get|post');
    RoutesDb::addRoute('deck_update_edit', 'admin/deck/update/edit.php', 'Update', 'edit','get|post');
    RoutesDb::addRoute('deck_upload', 'admin/deck/upload.php', 'Deck', 'deckUpload','get|post');
    RoutesDb::addRoute('deck_edit', 'admin/deck/edit.php', 'Deck', 'deckEdit','get|post');
    RoutesDb::addRoute('card_replace_image', 'admin/deck/edit/replace_card.php', 'Card', 'replaceImage','get|post');
    
    RoutesDb::addRoute('category_index', 'admin/category.php', 'Category', 'categories');
    RoutesDb::addRoute('category_add', 'admin/category/add.php', 'Category', 'addCategory','get|post');
    RoutesDb::addRoute('category_edit', 'admin/category/edit.php', 'Category', 'editCategory','get|post');
    RoutesDb::addRoute('subcategory_add', 'admin/subcategory/add.php', 'Category', 'addSubcategory','get|post');
    RoutesDb::addRoute('subcategory_edit', 'admin/subcategorys/edit.php', 'Category', 'editSubcategory','get|post');
?>