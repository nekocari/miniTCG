<?php
/**
 * Manager Class for System Messages
 * 
 * @author Cari
 */

require_once 'models/system_message.php';
require_once 'models/setting.php';

class SystemMessages {
    
    private static $system_messages = array(); 
    
    /**
     * adds a new message
     * 
     * @param string $identifier 
     * @param string $text_geraman
     * @param string $text_english
     */
    public static function addMessage($identifier, $text_en, $text_de) {
        self::$system_messages[$identifier] = new SystemMessage($identifier, $text_en, $text_de);
    }
    
    /**
     * get SystemMessage object by identifier
     *
     * @param string $identifier
     *
     * @return SystemMessage|boolean
     */
    public static function getSystemMessage($identifier) {
        if(key_exists($identifier, self::$system_messages)){
            return self::$system_messages[$identifier];
        }else{
            return false;
        }
    }
    
    public static function getSystemMessageText($identifier) {
        $system_message = self::getSystemMessage($identifier);
        if($system_message !== false){
            return $system_message->get_text(Setting::getByName('system_message_language')->getValue());
        }else{
            return 'TEXT MISSING';
        }
    }
       
}
?>