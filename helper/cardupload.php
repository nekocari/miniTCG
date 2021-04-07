<?php 
/**
 * helper class for cardupload
 * 
 * @author Cari
 * 
 */

require_once PATH.'models/setting.php';
require_once PATH.'models/carddeck.php';
require_once PATH.'helper/Parsedown.php';

class CardUpload {
    
    private $name;
    private $deckname;
    private $files = array();
    private static $cards_dir = PATH.CARDS_FOLDER;
    private static $accepted_file_types = array("image/png", "image/jpeg", "image/gif");
    private $db;
    private $cards_decksize, $cards_file_type, $upload_user, $subcategory, $type, $description, $description_html;
    
    public function __construct($name, $deckname, $files, $upload_user, $subcategory, $type, $description=null) {
        $this->name     = $name;
        $this->deckname = $deckname;
        $this->files    = $files;
        $this->db       = Db::getInstance();
        $this->cards_decksize = Setting::getByName('cards_decksize')->getValue();
        $this->cards_file_type = Setting::getByName('cards_file_type')->getValue();
        $this->upload_user = $upload_user;
        $this->subcategory = $subcategory;
        $this->type = $type;
        $this->description = $description;
        $parsedown = new Parsedown();
        $this->description_html = $parsedown->text($this->description);
    }
    
    /**
     * create a new dir for images within defined cards folder
     * @throws Exception
     */
    private function mkDir() {
        if(!mkdir(self::$cards_dir.$this->deckname, 0777) ){
            throw new Exception('Ordner existiert bereits.');
        }
    }
    
    /**
     * validates all form inputs
     * @throws Exception
     */
    private function validate() {
        if(!preg_match('/[A-za-z0-9\-_]+/', $this->deckname)){
            throw new Exception('Eingabe enthält ungültige Zeichen');
        }
        if(count($this->files) != ($this->cards_decksize + 1)){
            throw new Exception('Anzahl der Dateien enspricht nicht der Vorgabe.');
        }
        foreach($this->files as $file){
            if($file['name'] == ''){
                throw new Exception('Die Dateien sind unvollständig.');
            }
            if($file['error'] != UPLOAD_ERR_OK){
                throw new Exception('Es gab einen Fehler beim Upload der Bilder.');
            }
            if($file['type'] != "image/".$this->cards_file_type){
                throw new Exception('Ungültiger Dateityp gefunden. Erlaubt ist nur '.strtoupper($this->cards_file_type));
            }
        }
    }
    
    /**
     * stores all files on the server
     * @throws Exception
     * @return String|boolean - string contains exception message
     */
    public function store() {
        
        $this->validate(); // validate data
        $this->mkDir(); // make new dir
        foreach($this->files as $key => $file){
             
            // create new file path
            $filename = $this->deckname.str_replace('card_', '', $key).".".$this->cards_file_type;
            $file_path = self::$cards_dir.$this->deckname.'/'.$filename;
            
            if(!file_exists($file_path)){
                // move file from temp to new dir
                if(move_uploaded_file($file['tmp_name'], $file_path)){
                    chmod($file_path, 0644);
                }else{
                    throw new Exception('Datei konnte nicht dauerhaft gespeichert werden');
                }
            }else {
                throw new Exception('Datei existiert bereits!');
            }
            
        }
        
        // insert deck into DB
        $req = $this->db->prepare('INSERT INTO decks (name, deckname, creator, type, description, description_html) VALUES (:name, :deckname, :creator, :type, :description, :description_html)');
        $req->execute(array(':name'=>$this->name,':deckname'=>$this->deckname,':creator'=>$this->upload_user,':type'=>$this->type,':description'=>$this->description,':description_html'=>$this->description_html));
        $deck_id = $this->db->lastInsertId();
        
        // insert deck subcategory relation to DB
        $req = $this->db->prepare('INSERT INTO decks_subcategories (deck_id, subcategory_id) VALUES (:deck_id, :sub_id)');
        $req->execute(array(':deck_id'=>$deck_id,':sub_id'=>$this->subcategory));
          
        return true;    
    }
    
    public static function replaceCardImage($deck_id,$card_number,$file){
        // create new file path
        $deck = Carddeck::getById($deck_id);
        
        $file_type =  Setting::getByName('cards_file_type')->getValue();
        $filename = $deck->getDeckname().$card_number.".".$file_type;
        $file_path = self::$cards_dir.$deck->getDeckname().'/'.$filename;
        
        if(file_exists($file_path)){
            // delete old file
            unlink($file_path);
            // move file from temp to new dir
            if(move_uploaded_file($file['tmp_name'], $file_path)){
                chmod($file_path, 0644);
                return true;
            }else{
                throw new Exception('Datei konnte nicht dauerhaft gespeichert werden');
            }
        }else {
            throw new Exception('Originale Bilddatei unter Pfad '.$file_path.' nicht gefunden!');
            return false;
        }
    }
    
}

?>