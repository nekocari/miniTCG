<?php
/**
 * Handels the Texts for System Messages using Codes
 * 
 * define and return message texts depending on code in different languages
 * 
 * @author NekoCari
 */

class SystemMessageTextHandler {
    
    private static $instance = null;
    
    private $error_codes = array();
    private $error_texts = array();
    
    private function __construct() { }
    private function __clone() { }
    
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new SystemMessageTextHandler();
        }
        return self::$instance;
    }
    
    public function addCode($code,$texts){
            if(is_array($texts)){
                $this->error_codes[] = $code;
                foreach($texts as $lang=>$text){
                    $this->error_texts[$code][$lang] = $text;
                }
                return true;
            }else{
                throw new ErrorException('Provide Error Code Texts as array with language shortcut as key');
            }
    }
    
    public function getTextByCode($code,$lang='de'){
        if(in_array($code, $this->error_codes)){
            if(isset($this->error_texts[$code][$lang])){
                return $this->error_texts[$code][$lang];
            }else{
                return array_values($this->error_texts[$code])[0];
            }
        }else{
        	return array_values($this->error_texts['0'])[0];
            //throw new ErrorException('Error Code not found');
        }
    }
    
    
}
?>