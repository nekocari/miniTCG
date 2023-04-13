<?php
/**
 * 
 * Represents a Update Deck relation
 * 
 * @author NekoCari
 *
 */

class UpdateDeck extends DbRecordModel {
    
    protected $deck_id, $update_id;
    
    private $deck_obj, $update_obj;
    
    protected static
        $db_table = 'updates_decks',
        $db_pk = 'deck_id,update_id',
        $db_fields = array('deck_id','update_id'),
        $sql_order_by_allowed_values = array('update_id','deck_id');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public static function getRelationsByUpdateId($id) {
        return parent::getWhere('update_id = '.intval($id));
    }
    
    public static function getRelationsByDeckId($id) {
        return parent::getWhere('deck_id = '.intval($id));
    }
    
    public function getUpdate(){
        if(!$this->update_obj instanceof Update){
            $this->update_obj = Update::getById($this->update_id);
        }
        return $this->update_obj;
    }
    
    public function getDeck(){
        if(!$this->deck_obj instanceof Carddeck){
            $this->deck_obj = Carddeck::getById($this->deck_id);
        }
        return $this->deck_obj;
    }
    
    public function delete(){
        $req = $this->db->query('DELETE FROM '.self::$db_table.' WHERE deck_id = '.$this->deck_id.' AND update_id = '.$this->update_id);
        if($req->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }
    
}