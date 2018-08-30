<?php
/**
 * 
 * Represents a News Entry
 * 
 * @author Cari
 *
 */
require_once PATH.'models/setting.php';
require_once PATH.'models/member.php';

class News {
    
    private $id;
    private $date;
    private $author;
    private $title;
    private $text;
    private $text_html;
    private $db;
    
    public function __construct($id, $date, $author, $title, $text, $text_html = '') {
        $this->id           = $id;
        $this->date         = $date;
        $this->author       = $author;
        $this->title        = $title;
        $this->text         = $text;
        if($text_html != ''){
            $this->text_html= $text_html;
        }else{
            $parsedown = new Parsedown();
            $this->text_html = $parsedown->text($text);
        }
        $this->db           = DB::getInstance();
    }
    
    public static function getAll() {
        $news = array();
        $db = DB::getInstance();
        
        $req = $db->prepare('SELECT * FROM news_entries ORDER BY date DESC');
        $req->execute();
        if($req->rowCount() > 0){
            foreach($req->fetchAll(PDO::FETCH_OBJ) as $newsdata){
                $author = Member::getById($newsdata->author);                
                $news[] = new News($newsdata->id, $newsdata->date, $author, $newsdata->title, $newsdata->text, $newsdata->text_html);
            }
        }
        return $news;
    }
    
    public static function getById($id) {
        $entry = false;
        $db = DB::getInstance();
        
        $req = $db->prepare('SELECT * FROM news_entries WHERE id = :id');
        $req->execute(array(':id'=>$id));
        if($req->rowCount() > 0){
            $newsdata = $req->fetch(PDO::FETCH_OBJ);
            $author = Member::getById($newsdata->author);
            $entry = new News($newsdata->id, $newsdata->date, $author, $newsdata->title, $newsdata->text, $newsdata->text_html);
        }
        return $entry;
    }
    
    public static function add($title, $text){
        try {
            if(!empty($title) AND !empty($text)){
                $parsedown = new Parsedown();
                $text_html = $parsedown->text($text);
                $title = strip_tags($title);
                $db = Db::getInstance();
                $req = $db->prepare('INSERT INTO news_entries (author,title,text,text_html) VALUES (:author,:title,:text,:text_html)');
                try{
                    $req->execute(array(':author'=>$_SESSION['user']->id,':title'=>$title,':text'=>$text,':text_html'=>$text_html));
                    return true;
                }
                catch (PDOException $pdo_e){
                    return 'Einfügen in DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
                }
            }else{
                throw new Exception('Titel und Text müssen eingegeben werden!');
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function delete($id){
        try {
            if(!is_int($id)){
                $db = Db::getInstance();
                $req = $db->prepare('DELETE FROM news_entries WHERE id = :id');
                try{
                    $req->execute(array(':id'=> $id));
                    return true;
                }
                catch (PDOException $pdo_e){
                    return 'Löschen aus DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
                }
            }else{
                throw new Exception('Parameter ungültig!');
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function update($id,$title,$text){
        try {
            if(intval($id) AND !empty($title) AND !empty($text)){
                $parsedown = new Parsedown();
                $text_html = $parsedown->text($text);
                $title = strip_tags($title);
                $db = Db::getInstance();
                $req = $db->prepare('UPDATE news_entries SET title = :title, text = :text, text_html = :text_html WHERE id = :id');
                try{
                    $req->execute(array(':id'=> $id, ':title'=>$title, ':text'=>$text, ':text_html'=>$text_html));
                    return true;
                }
                catch (PDOException $pdo_e){
                    return 'Löschen aus DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
                }
            }else{
                throw new Exception('Parameter ungültig!');
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    
    public function getAuthor() {
        return $this->author;
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
        try {
            if(is_int($number)){
                $news = array();
                $db = DB::getInstance();
                $req = $db->prepare('SELECT * FROM news_entries ORDER BY date DESC LIMIT '.$number);
                try {
                    $req->execute();
                    foreach($req->fetchAll(PDO::FETCH_OBJ) as $newsdata){
                        $author = Member::getById($newsdata->author);
                        $news[] = new News($newsdata->id, $newsdata->date, $author, $newsdata->title, $newsdata->text, $newsdata->text_html);
                    }
                    return $news;
                }
                catch (PDOException $pdo_e){
                    return 'Einfügen in DB fehlgeschlagen. Datenbank meldet:<br>'.$pdo_e->getMessage();
                }
            }else{
                throw new Exception('Parmeter ungültig');
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function display($number){
        try {
            $entries = self::getLatestEntries($number);
            $template = file_get_contents(PATH.'views/templates/news_entry.php');
            foreach($entries as $entry){
                echo str_replace(array('[TITLE]','[TEXT]','[DATE]','[AUTHOR]'), array($entry->getTitle(),$entry->getText(),$entry->getDate(),$entry->getAuthor()->getName()), $template);
            }
        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }
    
}