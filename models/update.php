<?php
/**
 * 
 * Represents a Update
 * 
 * @author Cari
 *
 */
require_once 'models/carddeck.php';
require_once 'models/setting.php';

class Update {
    
    private $id;
    private $date;
    private $status;
    private $decks = array();
    private $db;
    
    
    public function __construct($id, $date, $status) {
        $this->id = $id;
        $this->date = $date;
        $this->status = $status;
        $this->decks = Carddeck::getInUpdate($this->id);
        $this->db = Db::getInstance();
    }
    
    public static function getAll() {
        try{
            $updates = array();
            $db = Db::getInstance();
            $req = $db->query('SELECT * FROM updates ORDER BY id DESC');
            foreach($req->fetchAll() as $update){
                $updates[] = new Update($update['id'],$update['date'],$update['status']);
            }
            return $updates;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function getById($id) {
        try{
            $db = Db::getInstance();
            $req = $db->prepare('SELECT * FROM updates WHERE id= :id');
            $req->execute(array(':id'=>$id));
            $update = $req->fetch(PDO::FETCH_ASSOC);
            return new Update($update['id'],$update['date'],$update['status']);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function create() {
        try{
            $db = Db::getInstance();
            $req = $db->prepare('INSERT INTO updates (date, status) VALUES(NOW(), \'new\' )');
            $req->execute();
            $update_id = $db->lastInsertId();
            return self::getById($update_id);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function delete($id) {
        try{
            $db = Db::getInstance();
            $req = $db->prepare('DELETE FROM updates WHERE id = :id AND status = \'new\' ');
            $req->execute(array(':id'=>$id));
            return true;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function publish($id) {
        try{
            $db = Db::getInstance();
            $req = $db->prepare('UPDATE updates SET status = \'public\', date = NOW() WHERE id = :id AND status = \'new\' ');
            $req->execute(array(':id'=>$id));
            
            $req = $db->prepare('SELECT deck_id FROM updates_decks WHERE update_id = :id');
            $req->execute(array(':id'=>$id));
            foreach($req->fetchAll(PDO::FETCH_ASSOC) as $deck){
                $db->query('UPDATE decks SET status = \'public\' WHERE id = '.$deck['deck_id']);
            }
            return true;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function addDeck($deck_id) {
        $this->decks[] = Carddeck::getById($deck_id);
        
        try{
            $req = $this->db->prepare('INSERT INTO updates_decks (deck_id, update_id) VALUES(:deck_id, :update_id) ');
            $req->execute(array(':update_id'=>$this->id, ':deck_id'=>$deck_id));
            return true;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        
    }
    
    public function removeDeck($deck_id) {
        $deck_key = array_search('$deck_id', $this->decks);
        foreach($this->decks as $key => $deck) {
            if($deck->getId() == $deck_id){
                $deck_key = $key;
                break;
            }
        }
        unset($this->decks[$deck_key]);
        
        try{
            $req = $this->db->prepare('DELETE FROM updates_decks WHERE deck_id = :deck_id AND update_id = :update_id ');
            $req->execute(array(':update_id'=>$this->id, ':deck_id'=>$deck_id));
            return true;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        
    }
    
    public function getDecks() {
        if(count($this->decks) == 0){
            $this->decks = Carddeck::getInUpdate($this->id);
        }
        return $this->decks;
    }
    
    public static function getLatest() {
        try{
            $updates = array();
            $db = Db::getInstance();
            $req = $db->query('SELECT * FROM updates ORDER BY id DESC LIMIT 1');
            if($req->rowCount()){
                $update = $req->fetch(PDO::FETCH_ASSOC);
                return new Update($update['id'],$update['date'],$update['status']);
            }else{
                throw new Exception('Kein Update gefunden');
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    /*
     * Getter
     */
    public function getDate() {
        return date(Setting::getByName('date_format')->getValue(),strtotime($this->date));
    }
    public function getStatus() {
        return $this->status;
    }
    public function getId() {
        return $this->id;
    }
    
}