<?php
/**
 * usefull text functions
 *
 * @author Cari
 *
 */


class TextProcessing {

    private $text, $init_length;
    
    public function __construct($text) {
        $this->text = $text;
        $this->init_length = strlen($text);
    }
    
    public function getText() {
        return $this->text;
    }
    
    /**
     * make the text usable as url path
     * @return string
     */
    public function formatAsUrl() {
        $url_save_chars = array_merge(range('a','z'),range(0,9),['_','-']);
        
        $specials = array('ä','ü','ö','ß',' ','ç','é','â','ê','î','ô','û','à','è','ì','ò','ù','ë','ï','ü','&');
        $specials_replacements = array('ae','ue','oe','ss','_','c','e','a','e','i','o','u','a','e','i','o','u','e','i','u','');
        // make everything lowercase and replace umlaute and spaces
        $url_save_text = trim(str_replace($specials, $specials_replacements, mb_strtolower($this->text,'UTF-8')));
        // remove every invalid character thats left from text (replace with "-")        
        for($i = 0; $i < strlen($url_save_text); $i++){
            if(!in_array($url_save_text[$i], $url_save_chars)){
                $url_save_text[$i] = "-";
            }
        }        
        return $url_save_text;
    }
    
    public function excerpt($start=0,$length=null) {
        if(is_null($length)){
            $length = strlen($this->text);
        }
        // TODO: improve so words will not get cut!
        $this->text = substr($this->text, $start, $length);
        if($start > 0){
            $this->text = '&hellip;'.$this->text;
        }
        if($length < $this->init_length){
            $this->text.= '&hellip;';
        }
        return $this->text;
    }
    
    /**
     * 
     * @param string $lang
     * @return string
     */
    public function relativeDate($lang='en',$timezone=DEFAULT_TIMEZONE){
        if(!in_array($lang,SUPPORTED_LANGUAGES)){
            $lang = 'en';
        }
        $format = AppSetting::getByName('date_format')->getValue();
        
        $date = new DateTime('today');
        $date->setTimezone(new DateTimeZone($timezone));
        $date_today = $date->format($format);
        $date->modify('-1 day');
        $date_yesterday = $date->format($format);
        
        $sub_tp = new TextProcessing('');
        
        if(substr($this->text,0,10) == $date_today){
            $sub_tp->setText('today');
        }elseif(substr($this->text,0,10) == $date_yesterday){
            $sub_tp->setText('yesterday');
        }
        
        $return_str = '';
        if(empty($sub_tp->getText())){
            $return_str = $this->text;
        }else{
            $return_str.= '<b>'.$sub_tp->translateAsKey($lang).'</b>'.substr($this->text,10);
        }
        return $return_str;
    }
    
    /**
     * 
     * @param string $text
     */
    public function setText($text){
        $this->text = $text;
    }
    
    /**
     * 
     * @param string $lang
     * @return string
     */
    public function translateAsKey($lang){
        if(!in_array($lang,SUPPORTED_LANGUAGES)){
            $lang = 'de';
        }
        if(key_exists($this->text, TRANSLATIONS) AND key_exists($lang, TRANSLATIONS[$this->text])){
            return TRANSLATIONS[$this->text][$lang];
        }else{
            return '{translation missing}';
        }
    }
    
}
?>