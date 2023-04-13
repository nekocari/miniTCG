<?php
/**
 * 
 * Represents a DeckType
 * 
 * @author NekoCari
 *
 */

class DeckType extends DbRecordModel {
    
    protected $id, $name, $size, $template_path;
    
    protected static
    $db_table = 'decks_types',
    $db_pk = 'id',
    $db_fields = array('id','name','size','template_path'),
    $sql_order_by_allowed_values = array('id','name','size');
    
    
    public static function getById($id) {
    	return parent::getByPk($id);
    }
    
    public function getByName($name) {
    	return parent::getByUniqueKey('name', $name);
    }
    
    public function getId() {
    	return $this->id;
    }
    
    public function getName() {
    	return $this->name;
    }
    
    public function getSize() {
    	return $this->size;
    }
    
    public function getTemplatePath() {
    	return $this->template_path;
    }
    
}