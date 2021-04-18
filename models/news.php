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
require_once PATH.'models/update.php';
require_once PATH.'helper/Parsedown.php';

class News extends DbRecordModel{
    
    protected $id, $date, $author, $title, $text, $text_html;
    private $author_obj, $update_obj;
    
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
        return parent::getAll(['date'=>'DESC'],intval($number));
    }
    
    public static function display($number){
        
        $entries = self::getLatestEntries($number);
        $template = file_get_contents(PATH.'views/templates/news_entry.php');
        $template_update = file_get_contents(PATH.'views/templates/news_entry_cardupdate.php');
        foreach($entries as $entry){
            $news_text = $entry->getText();
            if($entry->hasUpdate('public')){
                $update_content = SystemMessages::getSystemMessageText('news_update_not_logged_in');
                if(Login::loggedIn()){
                    $update_content = '';
                    foreach($entry->getRelatedUpdate('public')->getRelatedDecks() as $deck){
                        $update_content.= $deck->getMasterCard();
                    }
                }
                
                $news_text.= str_replace('[UPDATECONTENT]',$update_content,$template_update);
            }
            echo str_replace(array('[TITLE]','[TEXT]','[DATE]','[AUTHOR]'), array($entry->getTitle(),$news_text,$entry->getDate(),$entry->getAuthor()->getName()), $template);
            
        }
            
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