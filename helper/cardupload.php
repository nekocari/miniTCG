<?php 
require_once PATH.'models/setting.php';
class CardUpload {
    
    private $name;
    private $deckname;
    private $files = array();
    private $cards_dir = PATH.CARDS_FOLDER;
    private $accepted_file_types = array("image/png", "image/jpeg", "image/gif");
    private $db;
    private $cards_decksize;
    private $cards_file_type;
    private $upload_user;
    
    public function __construct($name, $deckname, $files, $upload_user) {
        $this->name     = $name;
        $this->deckname = $deckname;
        $this->files    = $files;
        $this->db       = Db::getInstance();
        $this->cards_decksize = Setting::getByName('cards_decksize')->getValue();
        $this->cards_file_type = Setting::getByName('cards_file_type')->getValue();
        $this->upload_user = $upload_user;
    }
    
    private function mkDir() {
        if(!mkdir($this->cards_dir.$this->deckname, 0777) ){
            throw new Exception('Ordner existiert bereits.');
        }
    }
    
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
    
    public function store() {
        try {
            $this->validate(); // validate data
            $this->mkDir(); // make new dir
            foreach($this->files as $key => $file){
                 
                // create new file path
                $filename = $this->deckname.str_replace('card_', '', $key).".".$this->cards_file_type;
                $file_path = $this->cards_dir.$this->deckname.'/'.$filename;
                
                if(!file_exists($file_path."/".$filename)){
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
            
            // insert into DB
            $req = $this->db->prepare('INSERT INTO decks (name, deckname, creator) VALUES (:name, :deckname, :creator)');
            $req->execute(array(':name'=>$this->name,':deckname'=>$this->deckname,':creator'=>$this->upload_user));
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        return true;    
    }
    
}

?>