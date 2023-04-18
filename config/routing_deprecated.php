<?php
    /* do not change this line */
    require_once 'core/routes.php';
    /*
     * add your own routes here! 
     * =========================
     * 
     * How to add a new route:
     * 
     * Routes::addRoute('IDENTIFIER','URL','controller','action','method');
     * IDENTIFIER   -> a unique key for the route, which can be used to create links within the app to the right url
     * URL          -> the url of your new page in the browser
     * CONTROLLER   -> for simple html pages you can use the pages controller and add your own action (a method) to it.
     * ACTION       -> method within the controller that will be executed; where your page is put together using the $this->layout()->render() method
     * METHOD		-> empty = get only, post for form processing etc, get|post enables both
     */
    
    
    Routes::addRoute('faq', 'faq.php', 'Pages', 'faq'); // empty template for you to fill
    Routes::addRoute('imprint', 'imprint.php', 'Pages', 'imprint'); // empty template for you to fill
    
    
    /**
     * DO NOT EDIT BELOW THIS LINE - unless you know what you are doing!
     *********************************************************************
     */
    
    
    Routes::addRoute('homepage', '', 'Pages', 'home');
    Routes::addRoute('not_found', 'error.php', 'Pages', 'notFound');
    Routes::addRoute('login_error', 'error_login.php', 'Pages', 'notLoggedIn');
    
    Routes::addRoute('deck_index', 'decks.php', 'Deck', 'index');
    Routes::addRoute('deck_by_category', 'decks/category.php', 'Deck', 'category');
    Routes::addRoute('deck_detail_page', 'decks/deck.php', 'Deck', 'deckpage');
    
    Routes::addRoute('member_index', 'memberlist.php', 'Member', 'memberlist');
    Routes::addRoute('member_profil', 'members/profil.php', 'Member', 'profil');
    
    Routes::addRoute('admin_dashboard', 'admin/', 'Admin', 'dashboard');
    Routes::addRoute('admin_settings', 'admin/settings.php', 'Admin', 'settings','get|post');
    Routes::addRoute('admin_member_index', 'admin/members/list.php', 'Admin', 'memberlist');
    Routes::addRoute('admin_member_search', 'admin/members/search.php', 'Admin', 'searchMember','get|post');
    Routes::addRoute('admin_member_edit', 'admin/members/edit.php', 'Admin', 'editMember','get|post');
    Routes::addRoute('admin_member_manage_rights', 'admin/members/manage_rights.php', 'Admin', 'manageRights','get|post');
    Routes::addRoute('admin_member_reset_password', 'admin/members/reset_password.php', 'Admin', 'resetPassword');
    Routes::addRoute('admin_member_gift_cards', 'admin/members/gift_cards.php', 'Admin', 'giftCards','get|post');
    Routes::addRoute('admin_member_gift_money', 'admin/members/gift_money.php', 'Admin', 'giftMoney','get|post');
    Routes::addRoute('admin_deck_index', 'admin/deck/list.php', 'Deck', 'adminDeckList');
    Routes::addRoute('admin_sql_import', 'admin/sql_import.php', 'Admin', 'importSql');
    
    Routes::addRoute('signup', 'signup.php', 'Login', 'signup','get|post');
    Routes::addRoute('signin', 'signin.php', 'Login', 'signin','get|post');
    Routes::addRoute('signout', 'signout.php', 'Login', 'signout');
    Routes::addRoute('login_activation', 'activation.php', 'Login', 'activate');
    Routes::addRoute('lost_password', 'lost_password.php', 'Login', 'password');
    Routes::addRoute('edit_userdata', 'member/userdata.php', 'Login', 'editUserdata');
    Routes::addRoute('delete_account', 'member/delete_account.php', 'Login', 'deleteAccount');
    
    Routes::addRoute('member_dashboard', 'member/dashboard.php', 'Login', 'dashboard');
    Routes::addRoute('member_cardmanager', 'member/cardmanager.php', 'Cardmanager', 'cardmanager','get|post');
    Routes::addRoute('member_mastercards', 'member/mastercards.php', 'Member', 'mastercards');
    Routes::addRoute('member_cardupdate', 'member/cardupdate.php', 'Update', 'take');
    
    Routes::addRoute('messages_recieved', 'member/inbox.php', 'Message', 'received');
    Routes::addRoute('messages_sent', 'member/outbox.php', 'Message', 'sent');
    Routes::addRoute('messages_write', 'member/new_message.php', 'Message', 'write');
    
    Routes::addRoute('card_search', 'cardsearch.php', 'Card', 'search','get|post');
    
    Routes::addRoute('shop', 'cardshop.php', 'shop', 'Shop');
    
    Routes::addRoute('game', 'games.php', 'Game', 'index');
    Routes::addRoute('game_lucky_number', 'games/lucky_number.php', 'Game', 'luckyNumber');
    Routes::addRoute('game_head_or_tail', 'games/head_or_tail.php', 'Game', 'headOrTail');
    Routes::addRoute('game_trade_in', 'games/trade_in.php', 'Game', 'tradeIn');
    
    Routes::addRoute('tradelog_member', 'member/tradelog.php', 'Tradelog', 'overview');
    Routes::addRoute('trades_recieved', 'member/trades_recieved.php', 'Trade', 'recieved');
    Routes::addRoute('trades_sent', 'member/trades_sent.php', 'Trade', 'sent');
    Routes::addRoute('trade', 'member/trade.php', 'Trade', 'add','get|post');
    
    Routes::addRoute('news_index', 'admin/news/list.php','News', 'index');
    Routes::addRoute('news_add', 'admin/news/add.php','News', 'add','get|post');
    Routes::addRoute('news_edit', 'admin/news/edit.php','News', 'edit','get|post');
    Routes::addRoute('news_link_update', 'admin/news/update.php','News', 'linkedUpdate','get|post');
    
    Routes::addRoute('level_index', 'admin/level.php', 'Level', 'level');
    Routes::addRoute('level_add', 'admin/level/add.php', 'Level', 'addLevel');
    Routes::addRoute('level_edit', 'admin/level/edit.php', 'Level', 'editLevel');
    
    Routes::addRoute('deck_update', 'admin/deck/update.php', 'Update', 'updates','get|post');
    Routes::addRoute('deck_update_add', 'admin/deck/update/add.php', 'Update', 'add','get|post');
    Routes::addRoute('deck_update_edit', 'admin/deck/update/edit.php', 'Update', 'edit','get|post');
    Routes::addRoute('deck_upload', 'admin/deck/upload.php', 'Deck', 'deckUpload','get|post');
    Routes::addRoute('deck_edit', 'admin/deck/edit.php', 'Deck', 'deckEdit','get|post');
    Routes::addRoute('card_replace_image', 'admin/deck/edit/replace_card.php', 'Card', 'replaceImage','get|post');
    
    Routes::addRoute('category_index', 'admin/category.php', 'Category', 'categories');
    Routes::addRoute('category_add', 'admin/category/add.php', 'Category', 'addCategory','get|post');
    Routes::addRoute('category_edit', 'admin/category/edit.php', 'Category', 'editCategory','get|post');
    Routes::addRoute('subcategory_add', 'admin/subcategory/add.php', 'Category', 'addSubcategory','get|post');
    Routes::addRoute('subcategory_edit', 'admin/subcategorys/edit.php', 'Category', 'editSubcategory','get|post');
?>