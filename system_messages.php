<?php
    // DO NOT EDIT !!! -----------------------
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
    
    
    
    // GENERAL - stuff like custom error codes
    SystemMessages::addMessage('1001', 'invalid character', 'Ungültiges Zeichen');
    
    
    // SystemMessages::addMessage('', '', '');
    // SystemMessages::getSystemMessageText('')
    
    
?>