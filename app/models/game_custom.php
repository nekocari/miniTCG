<?php
/**
 * 
 * Represents a Custom Game Entry
 * 
 * @author NekoCari
 *
 */

class GameCustom extends DbRecordModel {
    
	protected $id, $settings_id, $results, $view_file_path, $js_file_path;
    
    protected static 
        $db_table = 'games_custom',
        $db_pk = 'id',
        $db_fields = array('id','settings_id','results','view_file_path','js_file_path'),
        $sql_order_by_allowed_values = array('id','settings_id');
    
    /*
     * see Game model for static method getAllowedResultTypes() which returns: array('win-card:','win-money:','lost')
     * check with in_array($result_action,Game::getAllowedResultTypes())
     */
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @param int $id
     * @return GameCustom|NULL
     */
    public static function getById($id) {
    	return parent::getByPk($id);
    }
    
    /**
     * @param int $id
     * @return GameCustom|NULL
     */
    public static function getBySettingsId($id) {
    	return parent::getByUniqueKey('settings_id',$id);
    }
    
    /*
     * Getter
     */
    public function getId() {
    	return $this->id;
    }
    public function getSettingsId() {
    	return $this->settings_id;
    }
    public function getResultsPlain() {
    	return $this->results;
    }
    public function getResults() {
    	$results_expl = explode(',', $this->results);
    	foreach ($results_expl as $result_set){
    		$action = trim(substr($result_set, 0, strpos($result_set, '=>')));
    		$result = trim(substr($result_set, strpos($result_set, '=>')+2));
    		$results_arr[$action] = $result;
    	}
    	return $results_arr;
    }
    public function getJsFilePath() {
    	return $this->js_file_path;
    }
    public function getViewFilePath() {
    	return $this->view_file_path;
    }
    
    public function setResults($str){
    	if(is_string($str)){
    		$this->results = $str;
    	}else{
    		throw new ErrorException('results are not in string format');
    	}
    }
    
    public function setSettingsId($id){
    	$this->settings_id = $id;
    }
    
    public function isUsed(){
    	if(!empty($this->getResultsPlain()) OR !empty($this->getViewFilePath()) OR !empty($this->getJsFilePath()) ){
    		return true;
    	}
    	return false;
    }
    
}