<?php 
/**
 * Layout Class
 * 
 * used to combine view with header and footer
 * 
 * @author NekoCari
 *
 */

class Layout {
    
    private static $instance;
        
    private static $modeOptions = array(
	        'default',	
	        'landing',
	        'admin',
	        'clear'
    );
    private static $headerTemplate = array(
	        'default'=>'templates/layout/header.php',
	        'landing'=>'templates/layout/header.php',
	        'admin'=>'templates/layout/header.php'
    );
    private static $footerTemplate = array(
	        'default'=>'templates/layout/footer.php',
	        'landing'=>'templates/layout/footer.php',
	        'admin'=>'templates/layout/footer.php'
    );
    private $system_messages = array(
        'success'=>array(),
        'error'=>array(),
        'info'=>array()
    );
    
    private 
    	$supported_languages = SUPPORTED_LANGUAGES,
    	$lang = 'de',
    	$mode = 'default',
    	$darkmode = false,
    	$views_path = 'app/views/de/',
    	$html_title = 'miniTCG v0.2 alpha',
    	$js_files = array(),
    	$css_files = array(),
    	$breadcrumbs = array(),
    	$login, $dashboard_options, $members_online, $notifications;
    
        
    public function __construct($login) {
        $this->login = $login;
        //$this->dashboard_options = DashboardOptions::getInstance();
        //$this->members_online = MemberOnline::getVisible($this->login->getUser(),['date'=>'DESC']);
        $this->setLanguage();
        if($this->login->isLoggedIn()){
        	//$this->notifications = new NotificationList($this->login->getUser());
    	}
    }
    
    private function __clone(){}
    
    /**
     * 
     * @param string $mode [default|clear|landing]
     */
    public function setMode($mode) {
        if(in_array($mode, self::$modeOptions)){
            $this->mode = $mode;
        }
    }
    
    /**
     * Combines the templates based on the mode chosen and displays the full page
     * @param string $view path to content view file
     * @param array $values
     */
    public function render($view, $values = array()) {
        
        // set language to default in case view does not yet exist in current language
        if(!file_exists($this->views_path.$view)) {
            $this->setLanguage('de');
        }
        
        // continue if view is found
        if(file_exists($this->views_path.$view)){
            // turns tha value keys into variable names to use within the content view
            foreach($values as $key => $val) {
                $$key = $val;
            }
            
            switch($this->mode){
                case 'default':
                case 'landing':
                case 'admin':
                    require_once $this->views_path.self::$headerTemplate[$this->mode];
                    foreach($this->system_messages as $sys_messages_by_type){
                        foreach($sys_messages_by_type as $sys_msg){
                            $this->renderSystemMessages($sys_msg);
                        }
                    }
                    require_once $this->views_path.$view;
                    require_once $this->views_path.self::$footerTemplate[$this->mode];                                        
                    break;
                    
                case 'clear':
                    require_once $this->views_path.$view;
                    break;
                    
                default:
                    die('no layout mode set');
            }
        
        // view is invalid
        }else{
            die('Sorry! View not found. Please contact an admin!');
        }
    }
    
    /**
     * returns html for header
     * @return string
     */
    public function getJsLinks() {
        $js = '';
        foreach($this->js_files as $js_file){
            if(file_exists(PATH.'public/js/'.$js_file)){
                $js.= '<script language="JavaScript" src="public/js/'.$js_file.'"></script>'. PHP_EOL;
            }
        }
        return $js;
    }
    
    /**
     * returns html for header
     * @return string
     */
    public function getCssLinks() {
        $css = '';
        foreach($this->css_files as $css_file){
            if(file_exists(PATH.'public/css/'.$css_file)){
                $css.= '<link rel="stylesheet" href="public/css/'.$css_file.'" async>'. PHP_EOL;
            }
        }
        return $css;
    }
    
    /**
     * retuns the correct path for inclusion depending on the language set
     * @param string $partial path within the views without the language folder
     * @return string
     */
    public function getPartialPath($partial){
        $partial_path = PATH.$this->views_path.$partial;
        // set language to default in case view does not yet exist in current language
        if(!file_exists($partial_path)) {
            $partial_path = PATH.'app/views/de/'.$partial;
        }
        return $partial_path;
    }
    
    /**
     * retuns the correct path for inclusion depending on the language set and checks for existence
     * @param string $partial path within the views without the language folder
     * @return string 
     */
    public function partial($partial){
        $path = $this->getPartialPath($partial);
        if(file_exists($path)){
            return $path;
        }else{
        	throw new ErrorException('File not found',404);
        }
    }
    
    /**
     * choose which language to display the page in depending on viewer settings
     * @param string $lang
     */
    private function setLanguage($lang=null){
    	
        if($lang == null){
            // using personalized settings if logged in
            if($this->login->isloggedIn() AND is_array($this->supported_languages) AND in_array($this->login->getUser()->getLang(), $this->supported_languages)){
                $this->lang = $this->login->getUser()->getLang();
            // use broswer settings if possible
            }elseif(is_array($this->supported_languages) AND in_array($browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), $this->supported_languages)){
                $this->lang = $browser_lang;
            // as default use the first supported language
            }else{
                if(is_array($this->supported_languages)){
                    $this->lang = $this->supported_languages[0];
                }else{
                    $this->lang = $this->supported_languages;
                }
            }
            
        }elseif(in_array($lang, $this->supported_languages)){
            $this->lang = $lang;
        }
        
        $this->views_path = 'app/views/'.$this->lang.'/';
    }
    
    /* SYSTEM MESSAGES */
    /**
     * Add a system message to be displayed above all content
     * @param string $type [success|info|error]
     * @param mixed $code see config/system_messages.php
     */
    public function addSystemMessage($type, $code, $data = array(), $text_prepend = null){
    	$this->system_messages[$type][] = new SystemMessage($type, $code, $data, $text_prepend);
    }
    
    /**
     * 
     * displays a system message 
     * @param SystemMessage $system_message
     * @throws ErrorException
     */
    public function renderSystemMessages($system_message){
    	if($system_message instanceof SystemMessage){
    		$type = $system_message->getType();
    		$text = $system_message->getText($this->getLang());
    		$data = $system_message->getData();
    		$extra_text = $system_message->getAdditionalText();
    		$tpl_data = '';
    		
    		if(count($data) > 0){
    			foreach($data as $key=>$val){
    				$tpl_data.= 'data-'.$key.'="'.$val.'" ';
    			}
    		}
    		
    		$tpl_search = array('[MESSAGE]','[DATA]','[EXTRA_TEXT]');
    		$tpl_replace = array($text,$tpl_data,$extra_text);
    		
    		$template = file_get_contents(PATH.$this->views_path.'templates/message_'.$type.'.php');
    		echo str_replace($tpl_search, $tpl_replace, $template);
    	}else{
    		throw new ErrorException('system message is not a valid instance of SystemMessage');
    	}    	
    }
    
    /**
     * to be used within views directly
     * @param string $type [info|success|error]
     * @param string $text
     */
    public function renderMessage($type,$text){
    	$this->renderSystemMessages(new SystemMessage($type,0000,array(),$text));
    }
    
    public function getLang(){
        return $this->lang;
    }
    
    public function setTitle($title){
        $this->html_title = $title;
    }
    
    public function getTitle(){
        return $this->html_title;
    }
    
    public function addJsFile($filepath){
        if($this->js_files[] = $filepath){
            return true;
        }
    }
    
    public function getJsFiles(){
        return $this->js_files;
    }
    
    public function addCssFile($filepath){
        if($this->css_files[] = $filepath){
            return true;
        }
    }
    
    public function getCssFiles(){
        return $this->css_files;
    }
    
    /* BREADCRUMBS */
    
    /**
     * Sets breadcrumbs 
     * @param Breadcrumb[] $breadcrumbs
     */
    public function setBreadcrumbs($breadcrumbs){
    	if(is_array($breadcrumbs)){
    		foreach($breadcrumbs as $bc){
    			$this->breadcrumbs[] = new Breadcrumb($bc);
    		}
    	}
    }
    
    /**
     * returns breadcrumbs 
     * @return array
     */
    public function getBreadcrumbs(){
    	return $this->breadcrumbs;
    }
    
    /**
     * displays breadcrumbs html
     */
    public function displayBreadcrumbs() {
    	if(!empty($this->getBreadcrumbs())){
    		include($this->getPartialPath('templates/layout/breadcrumbs.php'));
    	}
    }
    
    /*
     * unused!
     */
    public function getNotifications() {
    	return $this->notifications;
    }
    
}

?>