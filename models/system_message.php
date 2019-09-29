<?php
/**
 * 
 * Represents a System Message
 * 
 * @author Cari
 *
 */

class SystemMessage {
    
    private $identifier;
    private $text_en;
    private $text_de;
    private static $accepted_languages = array('de','en');
    
    public function __construct($identifier, $text_en, $text_de) {
        $this->identifier   = $identifier;
        $this->text_en      = $text_en;
        $this->text_de      = $text_de;
    }
    
    /**
     * Get a text in a certain language
     * 
     * @param string $language - must be either 'en' for englisch or 'de' for german
     */
    public function get_text($language){
        if(!in_array($language,self::$accepted_languages)){
            $language = self::$accepted_languages[0];
        }
        $var_name = 'text_'.$language; 
        return $this->$var_name;
    }
    
   
}