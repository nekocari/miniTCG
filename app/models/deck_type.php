<?php
/**
 * 
 * Represents a DeckType
 * 
 * @author NekoCari
 *
 */

class DeckType extends DbRecordModel {
    
	protected $id, $name, $size, $cards_per_row, $card_image_width, $card_image_height, $master_image_width, $master_image_height, $template_path, $filler_type, $filler_path;
    
    protected static
    $db_table = 'decks_types',
    $db_pk = 'id',
    $db_fields = array('id','name','size','cards_per_row','card_image_width','card_image_height','master_image_width','master_image_height','template_path','filler_type','filler_path'),
    $sql_order_by_allowed_values = array('id','name','size'),
    $filler_type_allowed_values = array('identical','individual');
    
    private static $template_base_path = 'app/views/multilang/templates/';
    private static $template_base_path_absolute = PATH.'app/views/multilang/templates/';
    
    /**
     * @return DeckType
     */
    public function __construct(){
    	return parent::__construct();
    }
    
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
    
    public static function getTemplateBasePath($absolute=false) {
    	if(!$absolute){
    		return self::$template_base_path;
    	}else{
    		return self::$template_base_path_absolute;
    	}
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
    
    public function getCardWidth() {
    	return $this->card_image_width;
    }
    
    public function getCardHeight() {
    	return $this->card_image_height;
    }
    
    public function getMasterWidth() {
    	return $this->master_image_width;
    }
    
    public function getMasterHeight() {
    	return $this->master_image_height;
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
    
    /**
     * 
     * @return string|NULL
     */
    public function getTemplateContent() {
    	if(!empty($this->getTemplatePath()) AND file_exists(self::getTemplateBasePath(true).$this->getTemplatePath())){
    		return file_get_contents(self::getTemplateBasePath(true).$this->getTemplatePath());
    	}else{
    		return NULL;
    	}
    }
    
    public function validate() {
    	// if there is a template path make sure its valid
    	if(!empty($this->getTemplatePath()) AND !file_exists(self::getTemplateBasePath(true).$this->getTemplatePath())){
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
    	}elseif(empty($this->getFillerPath())){
    		$this->filler_path = NULL; // if there is none make sure it's null and not an empty string
    	}else{
    		// add a / at the end
    		if($this->filler_type == 'individual' AND substr($this->filler_path, strlen($this->filler_path)-1,1) != '/'){
    			$this->filler_path = $this->filler_path.'/';
    		}
    	}
    	// make sure there are no negative values set
    	$this->card_image_height = max($this->card_image_height,1);
    	$this->card_image_width = max($this->card_image_width,1);
    	$this->master_image_height = max($this->master_image_height,1);
    	$this->master_image_width = max($this->master_image_width,1);
    	return true;
    }
        
    public function create() {
    	if($this->validate()){
    		return parent::create();
    	}
    }
    
    public function update() {
    	if($this->validate()){
    		// check if deck size was changed
    		$old_state = self::getById($this->getId());
    		if($old_state->getSize() != $this->getSize()){
    			// update all decks with new size
    			$this->db->query("UPDATE " . Carddeck::getDbTableName() . " SET size = " . intval($this->getSize()) . " WHERE type_id = " . $this->getId());
    		}
    		return parent::update();
    	}
    }
    
}