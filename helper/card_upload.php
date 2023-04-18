<?php 
/**
 * helper class for cardupload
 * 
 * @author Cari
 * 
 */


class CardUpload {
    
    private $name;
    private $deckname;
    private $files = array();
    private static $cards_dir;
    private static $accepted_file_types = array("image/png", "image/jpeg", "image/gif");
    private $db;
    private $cards_decksize, $cards_file_type, $upload_user, $subcategory, $type_id, $description, $description_html;
    
    public function __construct($name, $deckname, $files, $upload_user, $subcategory, $type_id, $description=null) {
        $this->name     = $name;
        $this->deckname = $deckname;
        $this->files    = $files;
        $this->db       = Db::getInstance();
        $this->cards_decksize = DeckType::getById($type_id)->getSize();
        $this->cards_file_type = Setting::getByName('cards_file_type')->getValue();
        $this->upload_user = $upload_user;
        $this->subcategory = $subcategory;
        $this->type_id = $type_id;
        $this->description = $description;
        $parsedown = new Parsedown();
        $this->description_html = $parsedown->text($this->description);
        self::$cards_dir = PATH.Carddeck::getDecksFolder();
    }
    
    /**
     * creates the a folder recursively
     * @param string $dir
     * @throws Exception
     */
    private function create_path($dir,$start = PATH) {
        $dir = str_replace($start, '', $dir);
        $folders_arr = explode('/',$dir);
        $cur_dir = $start;
        foreach($folders_arr as $folder){
            $cur_dir = $cur_dir.'/'.$folder;
            if(!is_dir($cur_dir)){
                if(!mkdir($cur_dir, 0777)){
                    throw new Exception('mkdir_failed');
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * create a new dir for images within defined cards folder
     * @throws Exception
     */
    private function mkDir() {
        if(!is_dir(self::$cards_dir.$this->deckname)){
            // if cards folder does not exist create it
            if(!is_dir(self::$cards_dir)){
                $this->create_path(self::$cards_dir);
            }
            // change folder permissions if needed
            if(!is_writable(self::$cards_dir)){
                chmod(self::$cards_dir, 0777);
            }
            // create new subfolder
            if(!mkdir(self::$cards_dir.$this->deckname, 0777) ){
                throw new Exception('mkdir_failed');
            }
        }else{
            throw new Exception('dir_exists');
        }
    }
    
    
    /**
     * validates all form inputs
     * @throws Exception
     */
    private function validate() {
        if(!preg_match('/[A-za-z0-9\-_]+/', $this->deckname)){
            throw new Exception('input_invalid_character');
        }
        for($i = 1; $i <= $this->cards_decksize; $i++){
        	$file = $this->files['card_'.$i];
            if($file['name'] == ''){
                throw new Exception('admin_upload_file_incomplete');
            }
            if($file['error'] != UPLOAD_ERR_OK){
                throw new Exception('admin_upload_file_failed');
            }
            if($file['type'] != "image/".$this->cards_file_type){
                throw new Exception('file_type_invalid');
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
        
        // insert deck into DB
        $req = $this->db->prepare('INSERT INTO decks (name, deckname, creator, type_id, description, description_html) VALUES (:name, :deckname, :creator, :type_id, :description, :description_html)');
        $req->execute(array(':name'=>$this->name,':deckname'=>$this->deckname,':creator'=>$this->upload_user,':type_id'=>$this->type_id,':description'=>$this->description,':description_html'=>$this->description_html));
        $deck_id = $this->db->lastInsertId();
        
        // insert deck subcategory relation to DB
        $req = $this->db->prepare('INSERT INTO decks_subcategories (deck_id, subcategory_id) VALUES (:deck_id, :sub_id)');
        $req->execute(array(':deck_id'=>$deck_id,':sub_id'=>$this->subcategory));
        
        // create subfolder
        $this->mkDir(); 
        
        // move each file in newly created subfoler
        foreach($this->files as $key => $file){
        	if($file['name'] != ''){
	            // create new file path
	            $filename = $this->deckname.str_replace('card_', '', $key).".".$this->cards_file_type;
	            $file_path = self::$cards_dir.$this->deckname.'/'.$filename;
	            
	            if(!file_exists($file_path)){
	                // move file from temp to new dir
	                if(move_uploaded_file($file['tmp_name'], $file_path)){
	                    chmod($file_path, 0644);
	                }else{
	                    throw new Exception('admin_upload_file_failed');
	                }
	            }else {
	                throw new Exception('file_exists');
	            }
        	}
        }
        
        return true;    
    }
    
    public static function replaceCardImage($deck_id,$card_number,$file){
        // create new file path
        $deck = Carddeck::getById($deck_id);
        
        $file_type =  Setting::getByName('cards_file_type')->getValue();
        $filename = $deck->getDeckname().$card_number.".".$file_type;
        $file_path = PATH.Carddeck::getDecksFolder().$deck->getDeckname().'/'.$filename;
        
        if($file['name'] != ''){        	
        	if(file_exists($file_path)){
        		// delete old file
        		unlink($file_path);
        	}
        	// move file from temp to new dir
        	if(move_uploaded_file($file['tmp_name'], $file_path)){
        		chmod($file_path, 0644);
        		return true;
			}else{
        		throw new Exception('admin_upload_file_failed');
        		return false;
			}
        }
        
    }
    
}

?>