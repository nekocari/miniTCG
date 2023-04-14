<?php //TODO edit to match with new system
    // DO NOT EDIT THIS LINE -----------------
    require_once 'core/system_message.php';
    $sys_msg_text_handler = SystemMessageTextHandler::getInstance();
    //----------------------------------------
    
    /*
     * Here you can change the text the system will print out. 
     * 
     * --------
     * Example:
     * --------
     * $sys_msg_text_handler->addCode('some_text_name', ['en'=>'your english text here', 'de'=>'your german text here']);
     *                                              	   	   |<--- ENGLISH TEXT --->|  	   |<--- GERMAN TEXT --->|
     * 
     * Make sure to only change the parts wehre the example marks ENGLISH TEXT and/or GERMAN TEXT. 
     * Do not delete the ' at the start and end of your text. And do not use ' somewhere inside of your text!
     * Using HTML inside your text is possible.
     * 
     * You can add your own texts (or even possibly another language...)
     * 
     * This will NOT work:
     * $sys_msg_text_handler->addCode('some_text_name', your english text, your german text);
     * 
     */
    
    
    
    // texts used by the admin controller ----------------------------
    
    //-> do not override this first one!
    $sys_msg_text_handler->addCode('0', [
	    'en'=>'',
	    'de'=>''
    ]);
    
    // GENERAL
    $sys_msg_text_handler->addCode('no_elements', [
    		'en' => 'No elements found.',
    		'de' => 'Keine Elemente gefunden.'
    ]);
    $sys_msg_text_handler->addCode('changes_saved', [
    		'en' => 'Changes saved.',
    		'de' => 'Änderungen gespeichert.'
    ]);
    $sys_msg_text_handler->addCode('element_deleted', [
    		'en' => 'Element was deleted.',
    		'de' => 'Element wurde gelöscht.'
    ]);
    
    // SETTINGS
    $sys_msg_text_handler->addCode('admin_settings_updated', [
    		'en'=>'settings are now updated', 
    		'de'=>'Settings wurden aktualisiert.'
    ]);
    $sys_msg_text_handler->addCode('admin_settings_not_updated', [
    		'en'=>'settings could not be updated',
    		'de'=>'Settings konnten nicht aktualisiert werden.'
    ]);
    
    // ROUTING
    $sys_msg_text_handler->addCode('add_route_success', [
    		'en' => 'Route was created.',
    		'de' => 'Neue Route wurde angelegt.'
    ]);
    $sys_msg_text_handler->addCode('del_route_success', [
    		'en' => 'Route was deleted.',
    		'de' => 'Route wurde gelöscht.'
    ]);
    $sys_msg_text_handler->addCode('del_route_error', [
    		'en' => 'Route could not be deleted.',
    		'de' => 'Route konnte nicht gelöscht werden.'
    ]);
    $sys_msg_text_handler->addCode('del_route_denied', [
    		'en' => 'This route can not be deleted',
    		'de' => 'Diese Route kann nicht gelöscht werden.'
    ]);
    
    // USER
    $sys_msg_text_handler->addCode('admin_edit_user_success', [
		    'en'=>'userdata saved',
		    'de'=>'Daten wurden gespeichert.'
	]);
    $sys_msg_text_handler->addCode('admin_edit_user_failed', [
    		'en'=>'userdata not saved',
    		'de'=>'Daten nicht aktualisiert.'
    ]);
    $sys_msg_text_handler->addCode('admin_delete_user_success', [
    		'en'=>'user completly deleted',
    		'de'=>'Die Mitgliedsdaten wurden komplett gelöscht.'
    ]);
    $sys_msg_text_handler->addCode('admin_delete_user_failed', [
    		'en'=>'no userdata deleted',
    		'de'=>'Es wurden keine Daten gelöscht.'
    ]);
    $sys_msg_text_handler->addCode('admin_pw_reset_success', [
    		'en'=>'password was reset and mailed to user',
    		'de'=>'Passwort zurückgesetzt und an User gemailt.'
    ]);
    $sys_msg_text_handler->addCode('admin_pw_reset_failed', [
    		'en'=>'password could not be reset',
    		'de'=>'Passwort konnte nicht zurückgesetzt werden.'
    ]);
    
    // GIFT CARDS
    $sys_msg_text_handler->addCode('admin_gift_cards_success', [
		    'en'=>'credit successfull:',
		    'de'=>'Gutschrift erfolgt:'
	]); // NOTE the cards will be listet afterwards
    $sys_msg_text_handler->addCode('admin_gift_cards_failed', [
		    'en'=>'credit failed',
		    'de'=>'Gutschrift fehlgeschlagen.'
	]);
    $sys_msg_text_handler->addCode('admin_gift_cards_log_text', [
    		'en'=>'manual credit -> ',
    		'de'=>'manuelle GUTSCHRIFT -> '
    ]);
    
    // GIFT MONEY
    $sys_msg_text_handler->addCode('admin_gift_money_success', [
		    'en'=>'credit successfull:',
		    'de'=>'Gutschrift erfolgt:'
	]);// NOTE the amount will be listet afterwards
    $sys_msg_text_handler->addCode('admin_gift_money_failed', [
		    'en'=>'credit failed',
		    'de'=>'Gutschrift fehlgeschlagen.'
	]);
    $sys_msg_text_handler->addCode('admin_gift_money_log_text', [
    		'en'=>'manual credit -> ',
    		'de'=>'manuelle GUTSCHRIFT -> '
    ]);
    
    // CARDUPLOAD
    $sys_msg_text_handler->addCode('admin_upload_duplicate_key', [
		    'en'=>'a deck with the same id or deckname alerady exists',
		    'de'=>'Ein Deck mit selbem Kürzel oder selber ID existiert bereits.'
	]);
    $sys_msg_text_handler->addCode('admin_upload_file_count_error', [
    		'en'=>'number of files does not match required file count', 
    		'de'=>'Anzahl der Dateien enspricht nicht der Vorgabe.'
    ]);
    $sys_msg_text_handler->addCode('admin_upload_file_incomplete',[
    		'en'=>'incomplete transfer of files',
    		'de'=>'Die Dateien sind unvollständig.'
    ]);
    $sys_msg_text_handler->addCode('admin_upload_file_failed',[
    		'en'=>'an error occured while uploading the files',
    		'de'=>'Es gab einen Fehler beim Upload der Bilder.'
    ]);
    
    
    // texts used in the deck controller ------------------------------
    
    // DECK DISPLAY
    $sys_msg_text_handler->addCode('deck_no_elements', [
    		'en'=>'no decks to display', 
    		'de'=>'Keine Decks zum Anzeigen.'
    ]);
    
    // DECK UPLOAD
    $sys_msg_text_handler->addCode('deck_upload_success', [
    		'en'=>'deck successfully uploaded', 
    		'de'=>'Deck wurde erfolgreich hochgeladen.'
    ]);
    $sys_msg_text_handler->addCode('deck_upload_failed', [
    		'en'=>'upload not completed', 
    		'de'=>'Upload nicht abgeschlossen'
    ]);
    $sys_msg_text_handler->addCode('deck_edit_success', [
    		'en'=>'deck successfully updated', 
    		'de'=>'Daten wurden gespeichert'
    ]);
    $sys_msg_text_handler->addCode('deck_edit_failed', [
    		'en'=>'deck not updated', 
    		'de'=>'Daten nicht gespeichert'
    ]);
    
    
    /*
    // texts used in level controller --------------------------------------
    
    $sys_msg_text_handler->addCode('level_add_failed','level not added','Anlegen fehlgeschlagen.');
    $sys_msg_text_handler->addCode('level_edit_success', 'changes saved', 'Änderungen gespeichert.');
    $sys_msg_text_handler->addCode('level_edit_failed', 'changes not saved', 'Ändern fehlgeschlagen.');
    
    */
    
    // texts used in shop controller --------------------------------------
    
    $sys_msg_text_handler->addCode('cardshop_card_bought',[
    		'en'=>'You successfully bought:',
    		'de'=>'Du hast folgende Karte gekauft:'
    ]);
    $sys_msg_text_handler->addCode('cardshop_no_money',[
    		'en'=>'You don\'t own enough money to buy this card',
    		'de'=>'Du hast nicht genug Geld um diese Karte zu kaufen'
    ]);
    $sys_msg_text_handler->addCode('cardshop_card_gone', [
    		'en'=>'The card of your choice is no longer available.',
    		'de'=>'Die gewählte Karte ist nicht mehr verfügbar.'
    ]);
    $sys_msg_text_handler->addCode('cardshop_buy_error', [
    		'en'=>'Something went wrong!',
    		'de'=>'Etwas ist schiefgelaufen!'
    ]);
    $sys_msg_text_handler->addCode('cardshop_buy_log_text', [
    		'en'=>'Shop - You bought a card -> ',
    		'de'=>'Shop - Du hast eine Karte gekauft -> '
    ]);
    
    
    
    // texts used in game controller --------------------------------------
    
    $sys_msg_text_handler->addCode('game_waiting_minutes',[
    		'en'=>'minutes waiting time',
    		'de'=>'Minuten warten'
    ]);
    $sys_msg_text_handler->addCode('game_waiting_tomorrow',[
    		'en'=>'wait until tomorrow',
    		'de'=>'warte bis morgen'
    ]);
    $sys_msg_text_handler->addCode('game_play_now',[
    		'en'=>'play now',
    		'de'=>'jetzt spielen'
    ]);
    $sys_msg_text_handler->addCode('game_won',[
    		'en'=>'You <b>won!</b> <br>This is your price:',
    		'de'=>'Du hast <b>gewonnen!</b> <br>Hier dein Gewinn:'
    ]); // price displayed afterwards
    $sys_msg_text_handler->addCode('game_lost',[
    		'en'=>'Sorry, you did not win this time.',
    		'de'=>'Du hast leider nichts gewonnen.'
    ]);
    
    /*
    
    // text used by the login controller -----------------------------
    
    // LOGIN
    $sys_msg_text_handler->addCode('login_account_not_active', 'account is not active', 'Account ist nicht aktiv.');
    $sys_msg_text_handler->addCode('login_sign_in_failed', 'userdata invalid', 'Benutzerdaten sind ungültig.');
    $sys_msg_text_handler->addCode('login_sign_up_no_password', 'please set a password', 'Passwort muss gesetzt werden!');
    $sys_msg_text_handler->addCode('login_sign_up_password_not_matching', 'passwords do not match', 'Passwörter stimmen nicht überein');
    $sys_msg_text_handler->addCode('login_sign_up_username_or_mail_taken', 'username or e-mail is already taken', 'Benutzername oder E-Mailadresse bereits belegt.');
    $sys_msg_text_handler->addCode('login_new_password_success', 'a new password was send to your e-mail', 'Neues Passwort wurde an deine E-Mailadresse gesendet.');
    $sys_msg_text_handler->addCode('login_new_password_failed', 'no password to reset', 'Passwort nicht zurück gesetzt.');
    $sys_msg_text_handler->addCode('login_new_password_not_sent', 'password was reset, but mail could not be sent', 'Passwort zurück gesetzt, doch Mail konnte nicht gesendet werden.');
    
    // CARDMANAGER
    $sys_msg_text_handler->addCode('cardmanager_status_invalid', 'the selected status is invalid:', 'Der übergebene Status ist ungültig:'); // NOTE: status name will be displayed after the text!
    $sys_msg_text_handler->addCode('cardmanager_move_card_failed', 'card not moved:', 'Karte nicht verschoben:'); // NOTE: card name will be displayed after the text
    $sys_msg_text_handler->addCode('cardmanager_dissolve_collection_success', 'collection dissolved - the cards were moved to <i>NEW</i>', 'Sammlung aufgelöst. Die Karten sind nun wieder unter <i>NEW</i>');
    $sys_msg_text_handler->addCode('cardmanager_dissolve_collection_failed', 'collection could not be dissolved', 'Sammlung konnte nicht aufgelöst werden.');
    $sys_msg_text_handler->addCode('cardmanager_master_deck_success', 'you masterd a deck', 'Sammlung gemastert!');
    $sys_msg_text_handler->addCode('cardmanager_master_deck_failed', 'collection can not be masterd', 'Sammlung konnte nicht gemastert werden.');
    
    // USERDATA EDIT
    $sys_msg_text_handler->addCode('user_edit_data_success','userdata updated','Daten wurden gespeichert.');
    $sys_msg_text_handler->addCode('user_edit_data_failed','userdata not updated','Daten nicht aktualisiert.');
    $sys_msg_text_handler->addCode('user_edit_pw_success','password saved','Passwort wurde gespeichert.');
    $sys_msg_text_handler->addCode('user_edit_pw_failed','password not saved, error:','Passwort nicht aktualisiert. Folgender Fehler trat auf:'); // NOTE: more information about why it failed follows automaticaly
    
    
    
    // texts used by the message controller ------------------------------
    
    $sys_msg_text_handler->addCode('pm_not_found', 'message not found', 'Nachricht wurde nicht gefunden.');
    $sys_msg_text_handler->addCode('pm_mark_read', 'message marked as read', 'Nachricht als gelesen markiert.');
    $sys_msg_text_handler->addCode('pm_mark_read_failure', 'message could not be marked as read', 'Nachricht konnte nicht als gelesen markiert werden.');
    $sys_msg_text_handler->addCode('pm_deleted', 'message deleted', 'Nachricht wurde gelöscht.');
    $sys_msg_text_handler->addCode('pm_delete_failure', 'message not deleted', 'Nachricht konnte nicht gelöscht werden.');
    $sys_msg_text_handler->addCode('pm_send_success', 'message sent', 'Nachricht wurde gesendet.');
    $sys_msg_text_handler->addCode('pm_send_failure', 'message not sent', 'Nachricht wurde nicht gesendet.');
    $sys_msg_text_handler->addCode('pm_missing_input', 'the input is incomplete', 'Die Eingabe ist unvollständig.');
    
    
    
    // texts used by the news controller ----------------------------------
    
    $sys_msg_text_handler->addCode('news_update_not_logged_in', 'login to see update decks', 'Einloggen um Update zu sehen.');
    $sys_msg_text_handler->addCode('news_delete_failed', 'news could not be deleted', 'News konnte nicht gelöscht werden.');
    $sys_msg_text_handler->addCode('news_insert_failed', 'news could not be inserted', 'News konnte nicht gespeichert werden.');
    $sys_msg_text_handler->addCode('news_update_failed', 'news could not be updated', 'Änderung konnte nicht gespeichert werden.');
    
    
    
    // texts used by the trade controller ----------------------------------
    
    $sys_msg_text_handler->addCode('trade_decline_success', 'trade was declined', 'Tauschanfrage wurde abgelehnt.');
    $sys_msg_text_handler->addCode('trade_decline_failed', 'trade could not be declined', 'Tauschanfrage konnte nicht abgeleht werden.');
    $sys_msg_text_handler->addCode('trade_accept_success', 'trade was accepted', 'Tauschanfrage wurde angenommen.');
    $sys_msg_text_handler->addCode('trade_accept_failed', 'trade could not be accepted', 'Tauschanfrage konnte nicht angenommen werden.');
    $sys_msg_text_handler->addCode('trade_card_new_info', 'you will find the folling card in <i>NEW</i>:', 'Du findest die folgende Karte unter <i>NEW</i>:');
    $sys_msg_text_handler->addCode('trade_delete_success', 'trade successfully deleted', 'Tauschangebot wurde gelöscht.');
    $sys_msg_text_handler->addCode('trade_delete_failed', 'trade could not be deleted', 'Tauschangebot konnte nicht zurückgezogen werden.');
    $sys_msg_text_handler->addCode('trade_new_success', 'offer was sent', 'Taschanfrage wurde gesendet!');
    $sys_msg_text_handler->addCode('trade_new_failed', 'offer could not be sent, because at least one card is no longer available', 'Anfrage konnte nicht gestellt werden. Mindestens eine der Karten ist nicht mehr verfügbar.');
    $sys_msg_text_handler->addCode('trade_with_self', 'you can not trade with yourself', 'Du kannst nicht mit dir selbst tauschen.');
    
    */
    
    // texts used by the update controller ---------------------------------
    
    $sys_msg_text_handler->addCode('update_publish_success', [
    		'en'=>'decks in update published', 
    		'de'=>'Decks im Update wurden freigeschaltet.'
    ]);
    $sys_msg_text_handler->addCode('update_publish_failed', [
    		'en'=>'decks could not be published',
    		'de'=>'Decks nicht freigeschaltet.'
    		]);
    $sys_msg_text_handler->addCode('update_delete_success', [
    		'en'=>'update successfully deleted',
    		'de'=>'Update wurde gelöscht.'
    		]);
    $sys_msg_text_handler->addCode('update_delete_failed', [
    		'en'=>'update could not be deleted',
    		'de'=>'Update kann nicht gelöscht werden.'
    		]);
    $sys_msg_text_handler->addCode('update_new_failed', [
    		'en'=>'update could not be saved',
    		'de'=>'Update nicht angelegt.'
    		]);
    $sys_msg_text_handler->addCode('update_no_deck', [
    		'en'=>'there needs to be at least one deck in an update',
    		'de'=>'Ein Update muss mindestens ein Deck enthalten.'
    		]);
    $sys_msg_text_handler->addCode('update_edit_published', [
    		'en'=>'an update can not be edited after is was published',
    		'de'=>'Ein veröffentlichtes Update kann nicht mehr bearbeitet werden.'
    		]);
    $sys_msg_text_handler->addCode('update_take_failed', [
    		'en'=>'no cards received',
    		'de'=>'Keine Karten erhalten!'
    		]);
    $sys_msg_text_handler->addCode('update_take_success', [
    		'en'=>'Cards received - Have a look in your <i>CARDMANAGER</i>.',
    		'de'=>'Karten erhalten. Sieh in den <i>Kartenmanager</i>.'
    		]);
    
    /*
    
    // GENERAL - stuff like custom error codes
    
    $sys_msg_text_handler->addCode('input_invalid_character', 'input contains invalid character', 'Eingabe enthält ungültige Zeichen.');
    $sys_msg_text_handler->addCode('file_typ_invalid','invalid file type detected','Ungültiger Dateityp gefunden.');
    $sys_msg_text_handler->addCode('mkdir_failed', 'folder could not be created', 'Ordner konnte nicht anglegt werden.');
    $sys_msg_text_handler->addCode('dir_exists', 'folder already exists', 'Ordner existiert bereits');
    $sys_msg_text_handler->addCode('file_exists', 'file already exists', 'Datei existiert bereits');
    $sys_msg_text_handler->addCode('translate_recieved', 'recieved', 'erhalten');
    $sys_msg_text_handler->addCode('unexpected_error', 'unexpected error', 'unerwarteter Fehler');
    
    $sys_msg_text_handler->addCode('1001', 'invalid character', 'Ungültiges Zeichen');
    $sys_msg_text_handler->addCode('2001', 'invalid parameter', 'Ungültige Parameter.');
    $sys_msg_text_handler->addCode('8000', 'unallowed elements removed from input', 'Unerlaubte Elemente wurden aus Eingabe entfernt.');
    
    // TRADE ERROR CODES
    
    $sys_msg_text_handler->addCode('5001', 'status already changed', 'Der Status der Tauschanfrage wurde bereits geändert.');
    $sys_msg_text_handler->addCode('5002', 'you are not the recipient of this offer', 'Du kannst diese Anfrage nicht beantworten, da du nicht der Empfänger bist!');
    $sys_msg_text_handler->addCode('5003', 'requested card is no longer available', 'Angefragte Karte ist nicht verfügbar.');
    $sys_msg_text_handler->addCode('5004', 'offered card is no longer available', 'Angebotene Karte ist nicht verfügbar.');
    
    // CARD MANGAGMENT CODES
    $sys_msg_text_handler->addCode('6000', '', '');
    $sys_msg_text_handler->addCode('6001', '', '');
    $sys_msg_text_handler->addCode('6002', 'card already in collection', 'Karte befindet sich schon in Sammlung.');
    $sys_msg_text_handler->addCode('6003', 'collection is incomplete', 'Sammlung ist unvollständig.');
    $sys_msg_text_handler->addCode('6004', '', '');
    $sys_msg_text_handler->addCode('6005', '', '');
    
    // DB ERRORS
    
    $sys_msg_text_handler->addCode('9999', 'database reports an error', 'Datenbank meldet einen Fehler');
    */
    
?>