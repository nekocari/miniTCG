<?php
/**
 * 
 * Represents a DeckType
 * 
 * @author NekoCari
 *
 */

class DeckType extends DbRecordModel {
    
    protected $id, $name, $size, $cards_per_row, $template_path, $filler_type, $filler_path;
    
    protected static
    $db_table = 'decks_types',
    $db_pk = 'id',
    $db_fields = array('id','name','size','cards_per_row','template_path','filler_type','filler_path'),
    $sql_order_by_allowed_values = array('id','name','size'),
    $filler_type_allowed_values = array('identical','individual');
    
    
    public static function getFillerTypes() {
    	return self::$filler_type_allowed_values;
    }
    
    /**
     * 
     * @param int $id
     * @return DeckType|NULL
     */
    public static function getById($id) {
    	return parent::getByPk($id);
    }
    
    public static function getMaxSize() {
    	return self::getAll(['size'=>'DESC'],1)[0]->getSize();
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
    
    public function getPerRow() {
    	return $this->cards_per_row;
    }
    
    public function getTemplatePath() {
    	return $this->template_path;
    }
    
    public function getFillerType() {
    	return $this->filler_type;
    }
    
    public function getFillerPath() {
    	return $this->filler_path;
    }
    
    public function validate() {
    	// if there is a template path make sure its valid
    	if(!empty($this->getTemplatePath()) AND !file_exists(PATH.'app/views/'.$this->getTemplatePath())){
    		throw new ErrorException($this->getTemplatePath(),404);
    		return false;
    		// if there is none make sure it's null and not an empty string
    	}elseif(empty($this->getTemplatePath())){
    		$this->template_path = NULL;
    	}
    	// if there is a filler path make sure its valid
    	$card_img_folder = Setting::getByName('cards_folder')->getValue();
    	if(!empty($this->getFillerPath()) AND !file_exists(PATH.$card_img_folder.'/'.$this->getFillerPath())){
    		throw new ErrorException($this->getFillerPath(),404);
    		return false;
    		// if there is none make sure it's null and not an empty string
    	}elseif(empty($this->getFillerPath())){
    		$this->filler_path = NULL;
    	}
    	return true;
    }
        
    public function create() {
    	if($this->validate()){
    		return parent::create();
    	}
    }
    
    public function update() {
    	if($this->validate()){
    		return parent::update();
    	}
    }
    
}