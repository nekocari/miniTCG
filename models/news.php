<?php
/**
 * 
 * Represents a News Entry
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/member.php';
require_once PATH.'helper/Parsedown.php';

class News extends DbRecordModel{
    
    protected $id, $date, $author, $title, $text, $text_html;
    private $author_obj;
    
    protected static
        $db_table = 'news_entries',
        $db_pk = 'id',
        $db_fields = array('id','date','author','title','text','text_html'),
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
    public static function add($title, $text){
        
        if(!empty($title) AND !empty($text)){
            $parsedown = new Parsedown();
            $text_html = $parsedown->text($text);
            $title = strip_tags($title);
            
            $entry = new News();
            $entry->setPropValues(array('author'=>$_SESSION['user']->id,'title'=>$title,'text'=>$text,'text_html'=>$text_html));
            $entry_id = $entry->create();
            $entry->setPropValues(['id'=>$entry_id]);
            
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
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
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
        return parent::getAll(['date'=>'DESC',intval($number)]);
    }
    
    public static function display($number){
        
        $entries = self::getLatestEntries($number);
        $template = file_get_contents(PATH.'views/templates/news_entry.php');
        foreach($entries as $entry){
            echo str_replace(array('[TITLE]','[TEXT]','[DATE]','[AUTHOR]'), array($entry->getTitle(),$entry->getText(),$entry->getDate(),$entry->getAuthor()->getName()), $template);
        }
            
    }
    
}