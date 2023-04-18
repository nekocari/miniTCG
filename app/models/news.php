<?php
/**
 * 
 * Represents a News Entry
 * 
 * @author NekoCari
 *
 */

class News extends DbRecordModel{
    
    protected $id, $date, $utc, $author, $title, $text, $text_html;
    private $author_obj, $update_obj;
    
    protected static
        $db_table = 'news_entries',
        $db_pk = 'id',
        $db_fields = array('id','date','utc','author','title','text','text_html'),
        $sql_order_by_allowed_values = array('id','date');
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    /**
     * returns the new news object or false in case of failure
     * @param string $title 
     * @param string $text
     * @return News|boolean
     */
    public static function add($autor, $title, $text){        
        if($autor instanceof Member AND !empty($title) AND !empty($text)){
            $parsedown = new Parsedown();
            $text_html = $parsedown->text($text);
            $title = $parsedown->text(strip_tags($title));
            $date = new DateTime('now');
            
            $entry = new News();
            $entry->setPropValues(array('author'=>$autor->getId(),'title'=>$title,'utc'=>$date->format('c'),'text'=>$text,'text_html'=>$text_html));
            $entry->create();
            return $entry;
        }else{
            return false;
        }
    }
    
    public function update(){
        $parsedown = new Parsedown();
        $this->text_html = $parsedown->text($this->text);
        $this->title = strip_tags($this->title);
        
        return parent::update();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getDate($timezone = DEFAULT_TIMEZONE) {
    	$date = new DateTime($this->utc);
    	$date->setTimezone(new DateTimeZone($timezone));
        return $date->format(Setting::getByName('date_format')->getValue().' H:i');
    }
    
    public function getAuthor() {
        if(!$this->author_obj instanceof Member){
            $this->author_obj = Member::getById($this->author);
            if(is_null($this->author_obj)){
                $this->author_obj = new Member();
                $this->author_obj->setName('? ? ?');
            }
        }
        return $this->author_obj;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getText() {
        return $this->text_html;
    }
    
    public function getTextPlain() {
        return $this->text;
    }
    
    public static function getLatestEntries($number){
        return parent::getAll(['date'=>'DESC'],intval($number));
    }
    
    public static function display($number,$login){
    	if($login->getUser() instanceof Member){
    		$lang = $login->getUser()->getLang();
    	}else{ 
    		$lang = SUPPORTED_LANGUAGES[0];
    	}
        $entries = self::getLatestEntries($number);
        $template = self::getEntryTemplate($lang);
        $template_update = self::getUpdateTemplate($lang);
        foreach($entries as $entry){
            $news_text = $entry->getText();
            $news_author = $entry->getAuthor()->getName();
            if(!is_null($entry->getAuthor()->getProfilLink())){
                $news_author = '<a href="'.$entry->getAuthor()->getProfilLink().'">'.$news_author.'</a>';
            }
            if($entry->hasUpdate('public')){
            	$update_content = SystemMessageTextHandler::getInstance()->getTextByCode('news_update_not_logged_in',$lang) ;
                if($login->isLoggedIn()){
                    $update_content = '';
                    foreach($entry->getRelatedUpdate('public')->getRelatedDecks() as $deck){
                        $update_content.= $deck->getMasterCard();
                    }
                }
                
                $news_text.= str_replace('[UPDATECONTENT]',$update_content,$template_update);
            }
            echo str_replace(array('[TITLE]','[TEXT]','[DATE]','[AUTHOR]'), array($entry->getTitle(),$news_text,$entry->getDate(),$news_author), $template);
            
        }
            
    }
    
    private static function getEntryTemplate($lang) {
    	$filename = PATH.'app/views/'.$lang.'/templates/news_entry.php';
    	if(!file_exists($filename)){
    		$lang = key(SUPPORTED_LANGUAGES);
    	}
    	return file_get_contents(PATH.'app/views/'.$lang.'/templates/news_entry.php');
    }
    
    private static function getUpdateTemplate($lang) {
    	$filename = PATH.'app/views/'.$lang.'/templates/news_entry_cardupdate.php';
    	if(!file_exists($filename)){
    		$lang = key(SUPPORTED_LANGUAGES);
    	}
    	return file_get_contents(PATH.'app/views/'.$lang.'/templates/news_entry_cardupdate.php');
    }
    
    /**
     * returns an update object if news is related to one
     * @return Update|NULL
     */
    public function getRelatedUpdate($status='all') {
        if(is_null($this->update_obj)){
            $sql = 'SELECT u.* FROM updates_news un JOIN updates u ON u.id = un.update_id WHERE un.news_id = :news_id ';
            if($status == 'public'){
                $sql.= ' AND u.status = \'public\' ';
            }
            $req = $this->db->prepare($sql);
            $req->execute([':news_id'=>$this->getId()]);
            if($req->rowCount() == 1){
                $this->update_obj = $req->fetchObject('Update');
            }
        }
        return $this->update_obj;
    }
    
    /**
     * deletes an news update relation entry
     * @return boolean
     */
    public function removeUpdate($update_id) {
        $sql = 'DELETE FROM updates_news WHERE news_id = :news_id AND update_id = :update_id';
        $req = $this->db->prepare($sql);
        $req->execute([':news_id'=>$this->getId(),':update_id'=>intval($update_id)]);
        if($req->rowCount() > 0){
            $this->update_obj = null;
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * creates an news update relation entry
     * @return boolean
     */
    public function addUpdate($update_id) {
        $sql = 'INSERT INTO updates_news (news_id, update_id) VALUES (:news_id, :update_id)';
        $req = $this->db->prepare($sql);
        $req->execute([':news_id'=>$this->getId(),':update_id'=>intval($update_id)]);
        if($req->rowCount() > 0){
            $this->update_obj = null;
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * checks if there is an update related to this news
     * @return boolean
     */
    public function hasUpdate($status='all') {
        if(is_null($this->getRelatedUpdate($status))){
            return false;
        }else{
            return true;
        }
    }
    
}