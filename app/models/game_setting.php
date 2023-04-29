<?php
/**
 * 
 * Represents a Game Setting
 * 
 * @author NekoCari
 *
 */

class GameSetting extends DbRecordModel {
    
    protected $id, $game_key, $type, $wait_time, $route_identifier, $name_json, $description_json, $preview_img_path, $game_img_path;
    
    protected static 
        $db_table = 'game_settings',
        $db_pk = 'id',
        $db_fields = array('id','game_key','type','wait_time','route_identifier','name_json','description_json','preview_img_path','game_img_path'),
        $sql_order_by_allowed_values = array('id','game_key');
    
    private static $allowed_types = array('custom','lucky');
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * get a game setting by id
     * @param int $id
     * @return GameSetting|NULL
     */
    public static function getById($id) {
    	return parent::getByPk($id);
    }
    
    /**
     * get a game setting by game key
     * @param string $key
     * @return GameSetting|NULL
     */
    public static function getByKey($key) {
    	return parent::getByUniqueKey($key);
    }
    
    
    /**
     * check if game is playable
     * @return boolean
     */
    public function isPlayable() {
        if($this->getMinutesToWait() <= 0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * @todo: refactor
     * calculate the minutes to wait till game is playable again by member
     * @return number
     */
    public function getMinutesToWait($member) {
        
        $last_game_time = strtotime($this->date);
        
        if($this->wait_minutes === false){
            $start_today_time = strtotime(date('Y-m-d').' 00:00:00');
            if($last_game_time < $start_today_time){
                return 0;
            }else{
                return $minutes_to_wait = ceil(($start_today_time + 24*60*60 - time())/60);
            }
        }else{
            return $minutes_to_wait = ceil(($last_game_time + $this->wait_minutes*60 - time())/60);
        }
    }
    
    /**
     * returns true if game is only playable once per day
     * @return boolean
     */
    public function isDailyGame(){
        if(is_null($this->wait_minutes)){
            return true;
        }else{
            return false;
        }
    }
    
    
    /*
     * Getter
     */
    public function getId() {
    	return $this->id;
    }
    public function getType() {
    	return $this->type;
    }
    public function getWaitTime() {
    	return $this->wait_time;
    }
    public function getRouteIdentifier() {
    	return $this->route_identifier;
    }
    public function getName($lang='de') {
    	$name_arr = json_decode($this->name_json);
    	if(!array_key_exists($lang, $name_arr)){
    		return $name_arr[0];
    	}
    	return $name_arr[$lang];
    }
    public function getDescription($lang='de') {
    	$description_arr = json_decode($this->description_json);
    	if(!array_key_exists($lang, $description_arr)){
    		return $description_arr[0];
    	}
    	return $description_arr[$lang];
    }
    
    public static function getAllowedTypes(){
        return self::$allowed_game_results;
    }
    
    
    public function update(){
    	$this->validate();
    	parent::update();
    }
    
    private function validate() {
    	if(empty($this->wait_time) AND !is_null($this->wait_time)){
    		$this->wait_time = NULL;
    	}
    	if(!in_array($this->getType(), $this->getAllowedTypes())){
    		throw new ErrorException($this->getType().' is not an allowed game type!');
    	}
    }
    
    
    /**
     * @todo move the management of results to here form game model
     * @param LuckyGame|String $result 
     * @throws ErrorException
     * @return boolean|string[]|array[]|NULL[]|Card[][]
     */
    public function determineReward($result){
        /*
        // get result code of the current specific game
        if($result instanceof LuckyGame){
            $result = $result->getResult();
        }
        
        $result_action = preg_replace('/\d+/', '', $result);
        $result_amount = intval(str_replace($result_action, '', $result));
        $sys_msgs = SystemMessageTextHandler::getInstance();
        $this->reward_texts['won'] = $sys_msgs->getTextByCode('game_won',$this->getMember()->getLang());
        $this->reward_texts['lost'] = $sys_msgs->getTextByCode('game_lost',$this->getMember()->getLang());
                
        if(in_array($result_action,self::$allowed_game_results)){
            
            // pattern for return value
            $game_reward = array('type'=>'','text'=>'','cards'=>array());
            
            switch($result_action){
                case 'win-card:':
                    $game_reward['type'] = 'won';
                    $game_reward['text'] = $this->reward_texts['won'];
                    $cards = Card::createRandomCards($this->getMember(), $result_amount);
                    // Tradelog
                    foreach($cards as $card){
                    		$cardnames = $card->getName().' (#'.$card->getId().'), ';
                    }
                    $cardnames = substr($cardnames,0,-2);
                    Tradelog::addEntry($this->getMember(), 'game_won_log_text',GAMES_SETTINGS[$this->type]['name'].' -> '.$cardnames);
                    
                    if(!is_array($cards)){
                        $game_reward['cards'][] = $cards;
                    }else{
                        $game_reward['cards'] = $cards;
                    }
                    $this->getMember()->checkLevelUp();
                    break;
                    
                case 'win-money:':
                    // get name of currency
                    $currency_name = Setting::getByName('currency_name')->getValue();
                    // set values for result page
                    $game_reward['type'] = 'won';
                    $game_reward['text'] = $this->reward_texts['won'];
                    $game_reward['money'] = $result_amount.' '.$currency_name;
                    // add money to member balance 
                    $this->getMember()->addMoney($result_amount);
                    $this->getMember()->update();
                    //Tradelog
                    Tradelog::addEntry($this->getMember(), 'game_won_log_text',GAMES_SETTINGS[$this->type]['name'].' -> '.$game_reward['money']);
                    break;
                    
                case 'lost':
                    $game_reward['type'] = 'lost';
                    $game_reward['text'] = $this->reward_texts['lost'];
                    break;
                    
                default:
                    return false;
                    break;
            }
            $this->setDate(time());
            $this->update();
            return $game_reward;
            
        }else{
            throw new ErrorException('Result is not an allowed value');
        } */
    }
    
}