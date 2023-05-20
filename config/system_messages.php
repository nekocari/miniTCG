<?php 
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
     * Note, that something like this will NOT work:
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
    		'en'=>'settings are now updated: ', 
    		'de'=>'Settings wurden aktualisiert: '
    ]);
    $sys_msg_text_handler->addCode('admin_settings_not_updated', [
    		'en'=>'settings could not be updated: ',
    		'de'=>'Settings konnten nicht aktualisiert werden: '
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
    
    // MEMBERCARD
    $sys_msg_text_handler->addCode('admin_member_card_success', [
    		'en'=>'Membercard successfully uploaded',
    		'de'=>'Membercard erfolgreich hochgeladen'
    ]);
    $sys_msg_text_handler->addCode('admin_member_card_failed', [
    		'en'=>'Membercard not uploaded',
    		'de'=>'Membercard nicht hochgeladen'
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
    
    // GAME SETTINGS
    $sys_msg_text_handler->addCode('admin_games_add_success',[
    		'en'=>'A new game was created. Please make sure the routing identifier is valid!',
    		'de'=>'Ein neues Spiel wurde angelegt. Bitte sicherstellen, dass der Routing Identifier gültig ist!'
    ]);
    $sys_msg_text_handler->addCode('admin_games_deleted',[
    		'en'=>'Game successfully deleted',
    		'de'=>'Spiel erfolgreich entfernt.'
    ]);
    $sys_msg_text_handler->addCode('admin_game_result_value_invalid',[
    		'en'=>'The following result value is invalid: ',
    		'de'=>'Das folgende Resultat ist ungültig: '
    ]);
    $sys_msg_text_handler->addCode('admin_game_choice_type_invalid',[
    		'en'=>'Choice type is invalid: ',
    		'de'=>'Auswahltype ist ungültig: '
    ]);
    $sys_msg_text_handler->addCode('admin_game_to_few_results',[
    		'en'=>'There are not enough results!',
    		'de'=>'Es gibt nicht genügend Resultate.'
    ]);
    
    
    // texts used in deck type controller -----------------------------
    
    $sys_msg_text_handler->addCode('template_not_found',[
    		'en'=>'Template not found.',
    		'de'=>'Template Pfad nicht gefunden'
    ]);
    
    
    
    
    // texts used in the deck controller ------------------------------
    
    // DECK DISPLAY
    $sys_msg_text_handler->addCode('deck_no_elements', [
    		'en'=>'no decks to display',
    		'de'=>'Keine Decks zum Anzeigen.'
    ]);
    $sys_msg_text_handler->addCode('vote_submited', [
    		'en'=>'Thanks for voting.',
    		'de'=>'Danke für deine Stimme.'
    ]);
    $sys_msg_text_handler->addCode('wishlist_add_success', [
    		'en'=>'Deck added to wishlist.',
    		'de'=>'Deck zur Wunschliste hinzugefügt.'
    ]);
    $sys_msg_text_handler->addCode('wishlist_del_success', [
    		'en'=>'Deck removed from wishlist.',
    		'de'=>'Deck von der Wunschliste entfernt.'
    ]);
    
    // CARD UPLOAD (Replace)
    $sys_msg_text_handler->addCode('card_upload_success', [
    		'en'=>'card successfully uploaded',
    		'de'=>'Karte wurde erfolgreich hochgeladen.'
    ]);
    $sys_msg_text_handler->addCode('card_upload_failed', [
    		'en'=>'upload not completed',
    		'de'=>'Upload nicht abgeschlossen'
    ]);
    
    // DECK UPLOAD
    $sys_msg_text_handler->addCode('deck_add_success', [
        'en'=>'Deck successfully added. Please upload image files manually.',
        'de'=>'Deck wurde erfolgreich hinzugefügt. Bitte lade die Bilddateien manuel hoch.'
]);
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
    
    
    
    // texts used in level controller --------------------------------------
    
    $sys_msg_text_handler->addCode('level_add_failed',[
    		'en'=>'level not added',
    		'de'=>'Anlegen fehlgeschlagen.'
    ]);
    $sys_msg_text_handler->addCode('level_edit_success', [
    		'en'=>'changes saved', 
    		'de'=>'Änderungen gespeichert.'
    ]);
    $sys_msg_text_handler->addCode('level_edit_failed', [
    		'en'=>'changes not saved',
    		'de'=>'Ändern fehlgeschlagen.'
    ]);
    $sys_msg_text_handler->addCode('level_badge_upload_success', [
    		'en'=>'Level Badge upload successfull',
    		'de'=>'Level Badge erfolgreich hochgeladen'
    ]);
    $sys_msg_text_handler->addCode('level_badge_upload_failed', [
    		'en'=>'Level Badge upload failed',
    		'de'=>'Level Badge nicht hochgeladen'
    ]);
    
    
    
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
    		'en'=>'You <b>won!</b> <br>This is your prize:',
    		'de'=>'Du hast <b>gewonnen!</b> <br>Hier dein Gewinn:'
    ]); // price displayed afterwards
    $sys_msg_text_handler->addCode('game_lost',[
    		'en'=>'Sorry, you did not win this time.',
    		'de'=>'Du hast leider nichts gewonnen.'
    ]);
    $sys_msg_text_handler->addCode('game_won_log_text',[
    		'en'=>'Game won: ',
    		'de'=>'Spiel gewonnen: '
    ]);
    $sys_msg_text_handler->addCode('game_loss_log_text',[
    		'en'=>'Game loss: ',
    		'de'=>'Spiel Verlust: '
    ]);
    
    
    
    // text used by the login controller -----------------------------
    
    // LOGIN
    $sys_msg_text_handler->addCode('login_account_not_active', [
    		'en'=>'account is not active', 
    		'de'=>'Account ist nicht aktiv.'
    ]);
    $sys_msg_text_handler->addCode('login_sign_in_failed', [
    		'en'=>'userdata invalid',
    		'de'=>'Benutzerdaten sind ungültig.'
    ]);
    $sys_msg_text_handler->addCode('password_invalid', [
    		'en'=>'password invalid',
    		'de'=>'Passwort ungültig.'
    ]);
    $sys_msg_text_handler->addCode('login_sign_up_no_password', [
    		'en'=>'please set a password', 
    		'de'=>'Passwort muss gesetzt werden!'
    ]);
    $sys_msg_text_handler->addCode('login_sign_up_password_not_matching', [
    		'en'=>'passwords do not match', 
    		'de'=>'Passwörter stimmen nicht überein'
    ]);
    $sys_msg_text_handler->addCode('login_sign_up_username_or_mail_taken', [
    		'en'=>'username or e-mail is already taken', 
    		'de'=>'Benutzername oder E-Mailadresse bereits belegt.'
    ]);
    $sys_msg_text_handler->addCode('login_new_password_success', [
    		'en'=>'a new password was send to your e-mail', 
    		'de'=>'Neues Passwort wurde an deine E-Mailadresse gesendet.'
    ]);
    $sys_msg_text_handler->addCode('login_new_password_failed', [
    		'en'=>'no password to reset', 
    		'de'=>'Passwort nicht zurück gesetzt.'
    ]);
    $sys_msg_text_handler->addCode('login_new_password_not_sent', [
    		'en'=>'password was reset, but mail could not be sent',
    		'de'=>'Passwort zurück gesetzt, doch Mail konnte nicht gesendet werden.'
    ]);
    $sys_msg_text_handler->addCode('delete_account_error', [
    		'en'=>'Account can not be deleted. Please contact an administrator.',
    		'de'=>'Benutzerkonto kann nicht gelöscht werden. Bitte wende dich an einen Administrator.'
    ]);
    $sys_msg_text_handler->addCode('starter_cards_log_text', [
    		'en'=>'Starter cards recieved -> ',
    		'de'=>'starter Karten erhalten -> '
	]);
    
    // CARDMANAGER
    $sys_msg_text_handler->addCode('cardmanager_status_invalid', [
    		'en'=>'the selected status is invalid:', 
    		'de'=>'Der übergebene Status ist ungültig:'
    ]); // NOTE: status name will be displayed after the text!
    $sys_msg_text_handler->addCode('cardmanager_move_card_failed', [
    		'en'=>'card not moved:', 
    		'de'=>'Karte nicht verschoben:'
    ]); // NOTE: card name will be displayed after the text
    $sys_msg_text_handler->addCode('cardmanager_dissolve_collection_success', [
    		'en'=>'collection dissolved - the cards need to be sorted again. Deck: ', 
    		'de'=>'Sammlung aufgelöst. Die Karten müssen erneut sortiert werden. Deck: '
    ]);
    $sys_msg_text_handler->addCode('cardmanager_dissolve_collection_failed', [
    		'en'=>'collection could not be dissolved', 
    		'de'=>'Sammlung konnte nicht aufgelöst werden.'
    ]);
    $sys_msg_text_handler->addCode('cardmanager_master_deck_success', [
    		'en'=>'You masterd a deck: ', 
    		'de'=>'Sammlung gemastert:'
    ]);
    $sys_msg_text_handler->addCode('cardmanager_master_deck_failed', [
    		'en'=>'Collection can not be masterd.',
    		'de'=>'Sammlung konnte nicht gemastert werden.'
    ]);
    $sys_msg_text_handler->addCode('cardmanager_master_deck_log_text', [
    		'en'=>'Master of ',
    		'de'=>'Master von '
    ]); // NOTE: name of deck follows!
    
    // USERDATA EDIT
    $sys_msg_text_handler->addCode('user_edit_data_success',[
    		'en'=>'user data updated',
    		'de'=>'Daten wurden gespeichert.'
    ]);
    $sys_msg_text_handler->addCode('user_edit_data_failed',[
    		'en'=>'user data not updated',
    		'de'=>'Daten nicht aktualisiert.'
    ]);
    $sys_msg_text_handler->addCode('user_edit_settings_success',[
    		'en'=>'user settings updated',
    		'de'=>'Einstellungen wurden gespeichert.'
    ]);
    $sys_msg_text_handler->addCode('user_edit_settings_failed',[
    		'en'=>'user settings not updated',
    		'de'=>'Einstellungen nicht aktualisiert.'
    ]);
    $sys_msg_text_handler->addCode('user_edit_pw_success',[
    		'en'=>'password saved',
    		'de'=>'Passwort wurde gespeichert.'
    ]);
    $sys_msg_text_handler->addCode('user_edit_pw_failed',[
    		'en'=>'password not updated.',
    		'de'=>'Passwort nicht aktualisiert.'
    ]); 
    
    
    
    // texts used by the message controller ------------------------------
    
    $sys_msg_text_handler->addCode('pm_not_found', [
    		'en'=>'message not found',
    		'de'=>'Nachricht wurde nicht gefunden.'
    ]);
    $sys_msg_text_handler->addCode('pm_mark_read', [
    		'en'=>'message marked as read',
    		'de'=>'Nachricht als gelesen markiert.'
    ]);
    $sys_msg_text_handler->addCode('pm_mark_read_failure', [
    		'en'=>'message could not be marked as read',
    		'de'=>'Nachricht konnte nicht als gelesen markiert werden.'
    ]);
    $sys_msg_text_handler->addCode('pm_deleted', [
    		'en'=>'message deleted',
    		'de'=>'Nachricht wurde gelöscht.'
    ]);
    $sys_msg_text_handler->addCode('pm_delete_failure', [
    		'en'=>'message not deleted',
    		'de'=>'Nachricht konnte nicht gelöscht werden.'
    ]);
    $sys_msg_text_handler->addCode('pm_send_success', [
    		'en'=>'message sent',
    		'de'=>'Nachricht wurde gesendet.'
    ]);
    $sys_msg_text_handler->addCode('pm_send_failure', [
    		'en'=>'message not sent',
    		'de'=>'Nachricht wurde nicht gesendet.'
    ]);
    $sys_msg_text_handler->addCode('pm_missing_input', [
    		'en'=>'the input is incomplete',
    		'de'=>'Die Eingabe ist unvollständig.'
    ]);
    
    
    
    // texts used by the news controller ----------------------------------
    
    $sys_msg_text_handler->addCode('news_update_not_logged_in', [
    		'en'=>'login to view update decks', 
    		'de'=>'Einloggen um Update zu sehen.'
    ]);
    $sys_msg_text_handler->addCode('news_delete_failed', [
    		'en'=>'news could not be deleted', 
    		'de'=>'News konnte nicht gelöscht werden.'
    ]);
    $sys_msg_text_handler->addCode('news_insert_failed', [
    		'en'=>'news could not be inserted', 
    		'de'=>'News konnte nicht gespeichert werden.'
    ]);
    $sys_msg_text_handler->addCode('news_update_failed', [
    		'en'=>'news could not be updated', 
    		'de'=>'Änderung konnte nicht gespeichert werden.'
    ]);
    
    
    
    // texts used by the trade controller ----------------------------------
    
    $sys_msg_text_handler->addCode('trade_decline_success',  [
    		'en'=>'trade was declined',
    		'de'=>'Tauschanfrage wurde abgelehnt.'
    ]);
    $sys_msg_text_handler->addCode('trade_decline_failed',  [
    		'en'=>'trade could not be declined',
    		'de'=>'Tauschanfrage konnte nicht abgeleht werden.'
    ]);
    $sys_msg_text_handler->addCode('trade_accept_success',  [
    		'en'=>'trade was accepted',
    		'de'=>'Tauschanfrage wurde angenommen.'
    ]);
    $sys_msg_text_handler->addCode('trade_accept_failed',  [
    		'en'=>'trade could not be accepted',
    		'de'=>'Tauschanfrage konnte nicht angenommen werden.'
    ]);
    $sys_msg_text_handler->addCode('trade_card_new_info',  [
    		'en'=>'you will find the folling card your card manager:',
    		'de'=>'Du findest die folgende Karte im Karten Manager:'
    ]);
    $sys_msg_text_handler->addCode('trade_delete_success',  [
    		'en'=>'trade successfully deleted',
    		'de'=>'Tauschangebot wurde gelöscht.'
    ]);
    $sys_msg_text_handler->addCode('trade_delete_failed',  [
    		'en'=>'trade could not be deleted',
    		'de'=>'Tauschangebot konnte nicht zurückgezogen werden.'
    ]);
    $sys_msg_text_handler->addCode('trade_new_success',  [
    		'en'=>'offer was sent',
    		'de'=>'Taschanfrage wurde gesendet!'
    ]);
    $sys_msg_text_handler->addCode('trade_new_failed',  [
    		'en'=>'offer could not be sent, because at least one card is no longer available',
    		'de'=>'Anfrage konnte nicht gestellt werden. Mindestens eine der Karten ist nicht mehr verfügbar.'
    ]);
    $sys_msg_text_handler->addCode('trade_with_self',  [
    		'en'=>'you can not trade with yourself',
    		'de'=>'Du kannst nicht mit dir selbst tauschen.'
    ]);
    $sys_msg_text_handler->addCode('trade_declined_msg_text',  [
    		'en'=>'OFFER DECLINED! [{{MEMBER_NAME_RECIPIENT}}]({{MEMBER_LINK_RECIPIENT}}) does not trade {{REQUESTED_CARD_NAME}} for your {{OFFERED_CARD_NAME}}.',
    		'de'=>'ANFRAGE ABGELEHNT! [{{MEMBER_NAME_RECIPIENT}}]({{MEMBER_LINK_RECIPIENT}}) tauscht {{REQUESTED_CARD_NAME}} nicht gegen deine {{OFFERED_CARD_NAME}}.'
    ]);
    $sys_msg_text_handler->addCode('trade_accepted_msg_text',  [
    		'en'=>'OFFER ACCEPTED! [{{MEMBER_NAME_RECIPIENT}}]({{MEMBER_LINK_RECIPIENT}}) traded {{REQUESTED_CARD_NAME}} for your {{OFFERED_CARD_NAME}}.',
    		'de'=>'ANFRAGE ANGENOMMEN! [{{MEMBER_NAME_RECIPIENT}}]({{MEMBER_LINK_RECIPIENT}}) tauschte {{REQUESTED_CARD_NAME}} gegen deine {{OFFERED_CARD_NAME}}.'
    ]);
    $sys_msg_text_handler->addCode('trade_accepted_log_text',  [
    		'en'=>'TRADE with ',
    		'de'=>'TAUSCH mit '
    ]);
    
    
    
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
    
    
    
    // GENERAL - stuff like custom error codes
    
    $sys_msg_text_handler->addCode('invalid_data', [
    		'en' => 'Recieved data is invalid. ',
    		'de' => 'Übergebene Daten sind ungültig. '
    ]);
    $sys_msg_text_handler->addCode('level_up_info', [
    		'en' => 'You just reached a new level and recieved the following as a gift: ',
    		'de' => 'Du hast soeben ein neues Level erreicht und folgendes Geschenk erhalten: '
    ]);
    
    $sys_msg_text_handler->addCode('database_relations_exist_error', [
    		'en' => 'Action not possible, as long as there are active relations in the database. ',
    		'de' => 'Aktion nicht möglich, solange es noch aktive Verbindungen in der Datenbank gibt. '
    ]);
    
    
    
    $sys_msg_text_handler->addCode('input_invalid_character', [
    		'en' => 'input contains invalid character', 
    		'de' => 'Eingabe enthält ungültige Zeichen.'
    ]);
    $sys_msg_text_handler->addCode('file_typ_invalid',[
    		'en' => 'invalid file type detected',
    		'de' => 'Ungültiger Dateityp gefunden.'
    ]);
    $sys_msg_text_handler->addCode('mkdir_failed', [
    		'en' => 'folder could not be created', 
    		'de' => 'Ordner konnte nicht anglegt werden.'
    ]);
    $sys_msg_text_handler->addCode('dir_exists', [
    		'en' => 'folder already exists', 
    		'de' => 'Ordner existiert bereits'
    ]);
    $sys_msg_text_handler->addCode('file_exists', [
    		'en' => 'file already exists', 
    		'de' => 'Datei existiert bereits'
    ]);
    $sys_msg_text_handler->addCode('unexpected_error', [
    		'en' => 'unexpected error', 
    		'de' => 'unerwarteter Fehler'
    ]);
    
    // GENERAL ERROR CODES
    
    $sys_msg_text_handler->addCode('1001', [
    		'en' => 'invalid character', 
    		'de' => 'Ungültiges Zeichen'
    ]);
    $sys_msg_text_handler->addCode('2001', [
    		'en' => 'invalid parameter', 
    		'de' => 'Ungültige Parameter.'
    ]);
    $sys_msg_text_handler->addCode('8000', [
    		'en' => 'unallowed elements removed from input', 
    		'de' => 'Unerlaubte Elemente wurden aus Eingabe entfernt.'
    ]);
    
    // LOGIN ERROR CODES
    
    $sys_msg_text_handler->addCode('1011', [
    		'en' => 'Username or mail already taken.',
    		'de' => 'Benutername vergeben oder Mail bereits vorhanden.'
    ]);    
    $sys_msg_text_handler->addCode('1012', [
    		'en' => 'Password cannot be empty.',
    		'de' => 'Passwort kann nicht leer sein.'
    ]);
    $sys_msg_text_handler->addCode('1013', [
    		'en' => 'Passwords do not match.',
    		'de' => 'Passwörter stimmen nicht überein.'
    ]);
    $sys_msg_text_handler->addCode('1015', [
    		'en' => 'Mail with activation code could not be sent.',
    		'de' => 'Mail mit Aktivierungscode konnte nicht gesendet werden.'
    ]);
    
    // TRADE ERROR CODES
    
    $sys_msg_text_handler->addCode('5001', [
    		'en' => 'status already changed', 
    		'de' => 'Der Status der Tauschanfrage wurde bereits geändert.'
    ]);
    $sys_msg_text_handler->addCode('5002', [
    		'en' => 'you are not the recipient of this offer', 
    		'de' => 'Du kannst diese Anfrage nicht beantworten, da du nicht der Empfänger bist!'
    ]);
    $sys_msg_text_handler->addCode('5003', [
    		'en' => 'requested card is no longer available', 
    		'de' => 'Angefragte Karte ist nicht verfügbar.'
    ]);
    $sys_msg_text_handler->addCode('5004', [
    		'en' => 'offered card is no longer available', 
    		'de' => 'Angebotene Karte ist nicht verfügbar.'
    ]);
    
    // CARD MANGAGMENT CODES
    $sys_msg_text_handler->addCode('6000', [
    		'en' => '',
    		'de' => ''
    ]);
    $sys_msg_text_handler->addCode('6001', [
    		'en' => '',
    		'de' => ''
    ]);
    $sys_msg_text_handler->addCode('6002', [
    		'en' => 'card already in collection',
    		'de' => 'Karte befindet sich schon in Sammlung.'
    ]);
    $sys_msg_text_handler->addCode('6003', [
    		'en' => 'collection is incomplete',
    		'de' => 'Sammlung ist unvollständig.'
    ]);
    $sys_msg_text_handler->addCode('6004', [
    		'en' => '',
    		'de' => ''
    ]);
    $sys_msg_text_handler->addCode('6005', [
    		'en' => '',
    		'de' => ''
    ]);
    
    // DB ERRORS
    
    $sys_msg_text_handler->addCode('9999', [
    		'en' => 'database reports an error', 
    		'de' => 'Datenbank meldet einen Fehler'
    ]);
    
    
?>