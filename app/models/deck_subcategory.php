<?php
/**
 * 
 * Represents a Deck Subcategory relation
 * 
 * @author NekoCari
 *
 */

class DeckSubcategory extends DbRecordModel {
    
    protected $deck_id, $subcategory_id;
    
    private $deck_obj, $subcategory_obj;
    
    protected static
        $db_table = 'decks_subcategories',
        $db_pk = 'deck_id',
        $db_fields = array('deck_id','subcategory_id'),
        $sql_order_by_allowed_values = array('subcategory_id','deck_id');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    
    /**
     * get records for subcategory id
     * @param int $id Subcategory id
     * @return DeckSubcategory[]
     */
    public static function getRelationsBySubcategoryId($id) {
        return parent::getWhere('subcategory_id = '.intval($id));
    }
    
    /**
     * get records for deck id
     * @param int $id deck id
     * @return DeckSubcategory[]
     */
    public static function getRelationsByDeckId($id) {
        return parent::getWhere('deck_id = '.intval($id));
    }
    
    public function getSubcategory(){
        if(!$this->subcategory_obj instanceof Subcategory){
            $this->subcategory_obj = Subcategory::getById($this->subcategory_id);
        }
        return $this->subcategory_obj;
    }
    
    public function getDeck(){
        if(!$this->deck_obj instanceof Carddeck){
            $this->deck_obj = Carddeck::getById($this->deck_id);
        }
        return $this->deck_obj;
    }
    
    public function delete(){
        $req = $this->db->query('DELETE FROM '.self::$db_table.' WHERE deck_id = '.$this->deck_id.' AND subcategory_id = '.$this->subcategory_id);
        if($req->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }
    
    
}