<?php
/**
 * Represents a System Message
 * 
 * @author Cari
 */

require_once PATH.'core/system_message_text_handler.php';

class SystemMessage {
    
    private $type, $title, $code, $text, $data, $additional_text;
    private static $accepted_types = array('info','success','error');
    
    /**
     * 
     * @param string $type
     * @param mixed $code
     * @param array $data
     * @param string $additional_text
     * @throws ErrorException
     * @return SystemMessage
     */
    public function __construct($type, $code, $data=array(), $additional_text = null) {
        if(in_array($type, self::$accepted_types)){
            $this->type = $type;
            $this->code = $code;
            $this->data = $data;
            $this->additional_text = $additional_text;
        }else{
            throw new ErrorException('"'.$type.'" is NOT an accepted value for a System Message',1234);
        }
        return $this;
    }
    
    // basic setter
    public function setType($type) {
        if(in_array($type, self::$accepted_types)){
            $this->type = $type;
        }else{
            throw new ErrorException('"'.$type.'" is NOT an accepted value for a System Message',1234);
        }
    }
    
    
    public function setText($text) {
        $this->text = $text;
    }
    
    public function setData($data) {
        $this->data = $data;
    }
    
    public function getType() {
        return $this->type;
    }
    public function getText($lang) {
        if(!isset($this->text)){
            $handler = SystemMessageTextHandler::getInstance();
            $this->text = $handler->getTextByCode($this->code,$lang);
        }
        return $this->text;
    }
    public function getData() {
        return $this->data;
    }
    public function getAdditionalText() {
        return $this->additional_text;
    }
    
    
}
?>