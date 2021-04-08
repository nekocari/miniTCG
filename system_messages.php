<?php
    // DO NOT EDIT THIS LINE -----------------
    require_once 'inc/system_messages.php';
    //----------------------------------------
    
    /*
     * Here you can change the text the system will print out. 
     * 
     * --------
     * Example:
     * --------
     * SystemMessages::addMessage('some_text_name', 'your english text here', 'your german text here');
     *                                              |<--- ENGLISH TEXT --->|  |<--- GERMAN TEXT --->|
     * 
     * Make sure to only change the parts wehre the example marks ENGLISH TEXT and/or GERMAN TEXT. 
     * Do not delete the ' at the start and end of your text. And do not use ' somewhere inside of your text!
     * Using HTML inside your text is possible.
     * 
     * This will NOT work:
     * SystemMessages::addMessage('some_text_name', your english text, your german text);
     * 
     */
    
    
    
    // texts used by the admin controller ----------------------------
    
    // SETTINGS
    SystemMessages::addMessage('admin_settings_updated', 'settings are now updated', 'Settings wurden aktualisiert.');
    SystemMessages::addMessage('admin_settings_not_updated', 'settings could not be updated', 'Settings konnten nicht aktualisiert werden.');
    
    // USER
    SystemMessages::addMessage('admin_edit_user_success', 'userdata saved', 'Daten wurden gespeichert.');
    SystemMessages::addMessage('admin_edit_user_failed', 'userdata not saved', 'Daten nicht aktualisiert.');
    SystemMessages::addMessage('admin_delete_user_success', 'user completly deleted', 'Die Mitgliedsdaten wurden komplett gelöscht.');
    SystemMessages::addMessage('admin_delete_user_failed', 'no userdata deleted', 'Es wurden keine Daten gelöscht.');
    
    // GIFT CARDS
    SystemMessages::addMessage('admin_gift_cards_success', 'credit successfull:', 'Gutschrift erfolgt:'); // NOTE the cards will be listet afterwards
    SystemMessages::addMessage('admin_gift_cards_failed', 'credit failed', 'Gutschrift fehlgeschlagen.');    
    SystemMessages::addMessage('admin_gift_cards_log_text', 'manual credit', 'manuelle GUTSCHRIFT');
    
    
    
    // texts used in the deck controller ------------------------------
    
    // DECK DISPLAY
    SystemMessages::addMessage('deck_no_elements', 'no decks to display', 'Keine Decks zum Anzeigen.');
    
    // DECK UPLOAD
    SystemMessages::addMessage('deck_upload_success', 'deck successfully uploaded', 'Deck wurde erfolgreich hochgeladen.');
    SystemMessages::addMessage('deck_upload_failed', 'upload not completed', 'Upload nicht abgeschlossen');
    SystemMessages::addMessage('deck_edit_success', 'deck successfully updated', 'Daten wurden gespeichert');
    SystemMessages::addMessage('deck_edit_failed', 'deck not updated', 'Daten nicht gespeichert');
    
    
    
    // texts used in level controller --------------------------------------
    
    SystemMessages::addMessage('level_add_failed','level not added','Anlegen fehlgeschlagen.');
    SystemMessages::addMessage('level_edit_success', 'changes saved', 'Änderungen gespeichert.');
    SystemMessages::addMessage('level_edit_failed', 'changes not saved', 'Ändern fehlgeschlagen.');
    
    
    // texts used in shop controller --------------------------------------
    
    SystemMessages::addMessage('cardshop_card_bought','You successfully bought:','Du hast folgende Karte gekauft:');
    SystemMessages::addMessage('cardshop_no_money', 'You don\'t own enough money to buy this card', 'Du hast nicht genug Geld um diese Karte zu kaufen');
    SystemMessages::addMessage('cardshop_card_gone', 'The card of your choice is no longer available.', 'Die gewählte Karte ist nicht mehr verfügbar.');
    SystemMessages::addMessage('cardshop_buy_error', 'Something went wrong!', 'Etwas ist schiefgelaufen!');
    
    
    
    
    // text used by the login controller -----------------------------
    
    // LOGIN
    SystemMessages::addMessage('login_sign_in_failed', 'userdata invalid', 'Benutzerdaten sind ungültig.');
    SystemMessages::addMessage('login_sign_up_no_password', 'please set a password', 'Passwort muss gesetzt werden!');
    SystemMessages::addMessage('login_sign_up_password_not_matching', 'passwords do not match', 'Passwörter stimmen nicht überein');
    SystemMessages::addMessage('login_sign_up_username_or_mail_taken', 'username or e-mail is already taken', 'Benutzername oder E-Mailadresse bereits belegt.');
    SystemMessages::addMessage('login_new_password_success', 'a new password was send to your e-mail', 'Neues Passwort wurde an deine E-Mailadresse gesendet.');
    SystemMessages::addMessage('login_new_password_failed', 'no password to reset', 'Passwort nicht zurück gesetzt.');
    
    // CARDMANAGER
    SystemMessages::addMessage('cardmanager_status_invalid', 'the selected status is invalid:', 'Der übergebene Status ist ungültig:'); // NOTE: status name will be displayed after the text!
    SystemMessages::addMessage('cardmanager_move_card_failed', 'card not moved:', 'Karte nicht verschoben:'); // NOTE: card name will be displayed after the text
    SystemMessages::addMessage('cardmanager_dissolve_collection_success', 'collection dissolved - the cards were moved to <i>NEW</i>', 'Sammlung aufgelöst. Die Karten sind nun wieder unter <i>NEW</i>');
    SystemMessages::addMessage('cardmanager_dissolve_collection_failed', 'collection could not be dissolved', 'Sammlung konnte nicht aufgelöst werden.');
    SystemMessages::addMessage('cardmanager_master_deck_success', 'you masterd a deck', 'Sammlung gemastert!');
    SystemMessages::addMessage('cardmanager_master_deck_failed', 'collection can not be masterd', 'Sammlung konnte nicht gemastert werden.');
    
    // USERDATA EDIT
    SystemMessages::addMessage('user_edit_data_success','userdata updated','Daten wurden gespeichert.');
    SystemMessages::addMessage('user_edit_data_failed','userdata not updated','Daten nicht aktualisiert.');
    SystemMessages::addMessage('user_edit_pw_success','password saved','Passwort wurde gespeichert.');
    SystemMessages::addMessage('user_edit_pw_failed','password not saved, error:','Passwort nicht aktualisiert. Folgender Fehler trat auf:'); // NOTE: more information about why it failed follows automaticaly
    
    
    
    // texts used by the message controller ------------------------------
    
    SystemMessages::addMessage('pm_mark_read', 'message marked as read', 'Nachricht als gelesen markiert.');
    SystemMessages::addMessage('pm_deleted', 'message deleted', 'Nachricht wurde gelöscht.');
    
    
    
    // texts used by the news controller ----------------------------------
    
    SystemMessages::addMessage('news_delete_failed', 'news could not be deleted', 'News konnte nicht gelöscht werden.');
    SystemMessages::addMessage('news_insert_failed', 'news could not be inserted', 'News konnte nicht gespeichert werden.');
    SystemMessages::addMessage('news_update_failed', 'news could not be updated', 'Änderung konnte nicht gespeichert werden.');
    
    
    
    // texts used by the trade controller ----------------------------------
    
    SystemMessages::addMessage('trade_decline_success', 'trade was declined', 'Tauschanfrage wurde abgelehnt.');
    SystemMessages::addMessage('trade_decline_failed', 'trade could not be declined', 'Tauschanfrage konnte nicht abgeleht werden.');
    SystemMessages::addMessage('trade_accept_success', 'trade was accepted', 'Tauschanfrage wurde angenommen.');
    SystemMessages::addMessage('trade_accept_failed', 'trade could not be accepted', 'Tauschanfrage konnte nicht angenommen werden.');
    SystemMessages::addMessage('trade_card_new_info', 'you will find the folling card in <i>NEW</i>:', 'Du findest die folgende Karte unter <i>NEW</i>:');
    SystemMessages::addMessage('trade_delete_success', 'trade successfully deleted', 'Tauschangebot wurde gelöscht.');
    SystemMessages::addMessage('trade_delete_failed', 'trade could not be deleted', 'Tauschangebot konnte nicht zurückgezogen werden.');
    SystemMessages::addMessage('trade_new_success', 'offer was sent', 'Taschanfrage wurde gesendet!');
    SystemMessages::addMessage('trade_new_failed', 'offer could not be sent, because at least one card is no longer available', 'Anfrage konnte nicht gestellt werden. Mindestens eine der Karten ist nicht mehr verfügbar.');
    SystemMessages::addMessage('trade_with_self', 'you can not trade with yourself', 'Du kannst nicht mit dir selbst tauschen.');
    
    
    
    // texts used by the update controller ---------------------------------
    
    SystemMessages::addMessage('update_publish_success', 'decks in update published', 'Decks im Update wurden freigeschaltet.');
    SystemMessages::addMessage('update_publish_failed', 'decks could not be published', 'Decks nicht freigeschaltet.');
    SystemMessages::addMessage('update_delete_success', 'update successfully deleted', 'Update wurde gelöscht.');
    SystemMessages::addMessage('update_delete_failed', 'update could not be deleted', 'Update kann nicht gelöscht werden.');
    SystemMessages::addMessage('update_new_failed', 'update could not be saved', 'Update nicht angelegt.');
    SystemMessages::addMessage('update_no_deck', 'there needs to be at least one deck in an update', 'Ein Update muss mindestens ein Deck enthalten.');
    SystemMessages::addMessage('update_edit_published', 'an update can not be edited after is was published', 'Ein veröffentlichtes Update kann nicht mehr bearbeitet werden.');
    SystemMessages::addMessage('update_take_failed', 'no cards received', 'Keine Karten erhalten!');
    SystemMessages::addMessage('update_take_success', 'cards received - they are in category <i>NEW</i>', 'Karten erhalten. Bitte schau unter <i>NEW</i>.');
    
    
    
    // GENERAL - stuff like custom error codes
    
    SystemMessages::addMessage('1001', 'invalid character', 'Ungültiges Zeichen');
    SystemMessages::addMessage('2001', 'invalid parameter', 'Ungültige Parameter.');
    
    // TRADE ERROR CODES
    
    SystemMessages::addMessage('5001', 'status already changed', 'Der Status der Tauschanfrage wurde bereits geändert.');
    SystemMessages::addMessage('5002', 'you are not the recipient of this offer', 'Du kannst diese Anfrage nicht beantworten, da du nicht der Empfänger bist!');
    SystemMessages::addMessage('5003', 'requested card is no longer available', 'Angefragte Karte ist nicht verfügbar.');
    SystemMessages::addMessage('5004', 'offered card is no longer available', 'Angebotene Karte ist nicht verfügbar.');
    
    // DB ERRORS
    
    SystemMessages::addMessage('9999', 'database reported an error', 'Datenbank meldet einen Fehler');    
    
?>