<?php
/**
 * 
 * Represents a Update
 * 
 * @author NekoCari
 *
 */

class Update extends DbRecordModel {
    
    protected $id, $date, $utc, $status;
    
    private $update_deck_relations, $unliked_decks, $decks;
    
    protected static
        $db_table = 'updates',
        $db_pk = 'id',
        $db_fields = array('id','date','utc','status'),
        $sql_order_by_allowed_values = array('id','date','status');
    
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 
     * @param int $id
     * @return Update|NULL
     */
    public static function getById($id) {
        return parent::getByPk($id);
    }
    
    /**
     * returns all updates not linked to any news entry yet
     * @return Update[]
     */
    public static function getUnlinkedToNews(){
        $db = Db::getInstance();
        $updates = array();
        
        $sql = "SELECT u.* FROM updates u LEFT JOIN updates_news un ON u.id = un.update_id WHERE un.news_id IS NULL";
        $req = $db->query($sql);
        foreach($req->fetchAll(PDO::FETCH_CLASS,__CLASS__) as $update){
            $updates[] = $update;
        }
        return $updates;
    }
    
    /**
     * get latest public update
     * @return Update|NULL
     */
    public static function getLatest() {
    	$updates = parent::getWhere("status = 'public'",['date'=>'DESC']);
    	if(count($updates) > 0){
    		return $updates[0];
    	}else{
    		return null;
    	}
    }
    
    public function publish() {
        $decks = $this->getRelatedDecks();
        
        foreach($decks as $deck){
            $deck->setPropValues(['status'=>'public']);
            $deck->update();
        }
        
        $this->setPropValues(['status'=>'public']);
        $this->update();
        return true;
    }
    
    public function isPublic(){
    	if($this->getStatus() == 'public'){
    		return true;
    	}
    	return false;
    }
    
    
    public function addDeck($deck_id) {
        
        if(count(UpdateDeck::getRelationsByDeckId($deck_id)) == 0){
            $deck = Carddeck::getById($deck_id);
            
            if($deck instanceof Carddeck){
                $this->decks = null;
                
                $relation = new UpdateDeck();
                $relation->setPropValues(['deck_id'=>$deck_id,'update_id'=>$this->getId()]);
                return $relation->create();
                
            }else{
                throw new Exception('deck id is not valid');
            }
            
        }else {
            throw new Exception('deck is already part of an update');
        }
        
        return false;
        
    }
    
    public function removeDeck($deck_id) {
        if(array_key_exists($deck_id,$this->getRelatedDecks())){
            unset($this->decks[$deck_id]);
            $relation = UpdateDeck::getByUniqueKey('deck_id', $deck_id);
            return $relation->delete();
        }else{
            throw new Exception('deck not found in update');
            return false;
        }
    }

    /**
     * get all decks related to the current update
     * @return Carddeck[] with deck_id as key
     */
    public function getRelatedDecks($refresh = false) {
        if(is_null($this->decks) OR $refresh){
        	if(is_null($this->update_deck_relations) OR $refresh){
                $this->update_deck_relations = UpdateDeck::getRelationsByUpdateId($this->getId());
            }
            foreach($this->update_deck_relations as $relation){
                $this->decks[$relation->getDeck()->getId()] = $relation->getDeck();
            }
        }
        return $this->decks;
    }
    
    public function getDeckCount() {
    	return count($this->getRelatedDecks());
    }
    
    /**
     * Decks not linked to any update
     * @return Carddeck[]
     */
    public function getUnlinkedDecks(){
    	return Carddeck::getNotInUpdate();
    }
    
    /*
     * Getter
     */
    public function getDate($timezone = DEFAULT_TIMEZONE) {
    	$date = new DateTime($this->utc);
    	$date->setTimezone(new DateTimeZone($timezone));
        return $date->format(Setting::getByName('date_format')->getValue());
    }
    public function getStatus() {
        return $this->status;
    }
    public function getId() {
        return $this->id;
    }
    
}