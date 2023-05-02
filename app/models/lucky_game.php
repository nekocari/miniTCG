<?php
/**
 * 
 * Represents a LuckyGame
 * 
 * 
 * @author Neko Cari
 * 
 *
 */


class LuckyGame extends DbRecordModel {
	
	protected $id, $settings_id, $choices_json, $results_json, $choice_type;
	private $game_settings, $member_obj, $member_game, $choices_arr, $results_arr;
	
	protected static
	$db_table = 'games_lucky',
	$db_pk = 'settings_id',
	$db_fields = array('id','settings_id','choices_json','results_json','choice_type'),
	$sql_order_by_allowed_values = array('id','settings_id');
	
    private static $allowed_choice_types = array('text','image');
    
    /**
     * 
     */
    public function __construct() { 
    	parent::__construct();
    }
    
    public function getSettingsId() {
    	return $this->settings_id;
    }
    
    public function getSettings() {
    	if(is_null($this->game_settings)){
    		$this->game_settings = GameSetting::getById($this->getSettingsId());
    	}
    	return $this->game_settings;
    }
    
    public function getName($lang) {
        return $this->getSettings()->getName($lang);
    }
    
    public function getDescription($lang) {
        return $this->getSettings()->getDescription($lang);
    }
    
    public function getChoiceType() {
    	return $this->choice_type;
    }
    
    public function getChoicesArr() {
    	if(is_null($this->choices_arr)){
    		$this->choices_arr = json_decode($this->choices_json);
    	}
    	return $this->choices_arr;
    }
    
    public function getResultsArr() {
    	if(is_null($this->results_arr)){
    		$this->results_arr = json_decode($this->results_json);
    	}
    	return $this->results_arr;
    }
    
    /**
     * returns als choice elements either as text or image tag
     * @return string[]
     */
    public function getChoices() {
        if(!in_array($this->choice_type, self::$allowed_choice_types)){
            $this->choice_type = self::$allowed_choice_types[0];
        }
        switch($this->choice_type){
            case 'text':
                return $this->getChoicesArr();
                break;
            case 'image':
                $elements = array();
                foreach($this->getChoicesArr() as $key => $element){
                    $elements[$key] = '<img src="'.$element.'">';
                }
                return $elements;
                break;
        }
    }
    
    public function getResult($key) {
    	$results = $this->getResultsArr();
    	shuffle($results);
        return $results[$key];
    }
    
    /**
     * @return Member|NULL
     */
    public function getMember() {
    	return $this->member_obj;
    }
    
    /**
     * @return Game|NULL
     */
    public function getMemberGame() {
    	if(is_null($this->member_game) AND $this->getMember() instanceof Member){
    		$this->member_game = Game::getById($this->getSettings()->getKey(), $this->getMember()->getId());
    	}
    	return $this->member_game;
    }
    
    /**
     * @param Member $member
     */
    public function setMember($member) {
    	if($member instanceof Member) {
    		$this->member_obj = $member;
    	}
    }
    
    /**
     * @param int $id
     */
    public function setSettingsId($id){
    	// TODO: make sure settings_id exists?
    	$this->settings_id = intval($id);
    }
    
    /**
     * @param string $type
     */
    public function setChoiceType($type){
    	if(in_array($type, self::$allowed_choice_types)){
    		$this->choice_type = $type;
    	}
    }
    
    /**
     * @param string|string[] $values
     */
    public function setChoices($values){
    	if(is_string($values)){
    		$choices_arr = array();
    		$choices_expl = explode(',', $values);
    		foreach ($choices_expl as $choice){
    			$choices_arr[] = trim($choice);
    		}
    		$values = $choices_arr;
    	}
    	$this->choices_arr = $values;
    	$this->choices_json = json_encode($this->choices_arr);
    }
    
    /**
     * @param string|string[] $values
     */
    public function setResults($values){
    	if(is_string($values)){
    		$results_arr = array();
    		$results_expl = explode(',', $values);
    		foreach ($results_expl as $result){
    			$results_arr[] = trim($result);
    		}
    		$values = $results_arr;
    	}
    	$this->results_arr = $values;
    	$this->results_json = json_encode($this->results_arr);
    }
    
    public function validate() {
    	if(!in_array($this->getChoiceType(), self::$allowed_choice_types)){
    		throw new ErrorException($this->getChoiceType(),4005);
    	}
    	if(count($this->getChoicesArr()) > count($this->getResultsArr())){
    		throw new ErrorException('to few results',4006);
    	}
    	if(count($this->getResultsArr())> 0){
    		$allowed_results = Game::getAllowedResults();
    		foreach($this->getResultsArr() as $result){
    			if(!in_array(preg_replace('/\d+/', '', $result),$allowed_results)){
    				throw new ErrorException($result,4004);
    			}
    		}
    	}
    }
    
    public function create() {
    	$this->validate();
    	parent::create();
    }
    public function update() {
    	$this->validate();
    	parent::update();
    }
    
}