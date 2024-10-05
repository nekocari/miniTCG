<?php
/**
 * 
 * Represents a Game
 * 
 * @author NekoCari
 *
 */

class Game extends DbRecordModel {
    
    protected $id, $type, $member, $date, $utc;
    private $member_obj, $game_settings, $reward_texts = array('lost' => null, 'won' => null);
    
    protected static 
        $db_table = 'members_games',
        $db_pk = 'id',
        $db_fields = array('id','type','member','date','utc'),
        $sql_order_by_allowed_values = array('id','type','member','date');
    
    private static $allowed_game_results = array('win-card:','win-money:','lost');
    
    public function __construct() {
        parent::__construct();
    }
    
    public static function getAllowedResultTypes(){
    	return self::$allowed_game_results;
    }
    
    /**
     * get game datafrom database using game type and member id
     * 
     * @param string $type - Name of the game (like 'lucky_number')
     * @param int $member_id - Name of the game (like 'lucky_number')
     * 
     * @return Game
     */
    public static function getById($type, $member_id) {
        
        $game_data = parent::getWhere("type = '$type' AND  member = ".intval($member_id));
        
        if(!is_null($game_data) AND count($game_data) == 1){
            $game = $game_data[0];
        }else{
            $date = date('Y-m-d',time()-(60*60*24)).' 00:00:00';
            $game = new Game();
            $game->setPropValues(array('member'=>$member_id,'type'=>$type,'date'=>$date));
            $game->create();
        }
        
        return $game;
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
     * calculate the minutes to wait till game is playable again
     * @return number
     */
    public function getMinutesToWait() {
    	
    	$last_game_time = strtotime($this->date);
    	
    	if($this->getGameSettings()->getWaitTime() === false){
    		$start_today_time = strtotime(date('Y-m-d').' 00:00:00');
    		if($last_game_time < $start_today_time){
    			return 0;
    		}else{
    			return $minutes_to_wait = ceil(($start_today_time + 24*60*60 - time())/60);
    		}
    	}else{
    		return $minutes_to_wait = ceil(($last_game_time + $this->getGameSettings()->getWaitTime()*60 - time())/60);
    	}
    }
    
    /**
     * returns true if game is only playable once per day
     * @return boolean
     */
    public function isDailyGame(){
        return $this->getGameSettings()->isDailyGame();
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
    public function getMember() {
		if(!$this->member_obj instanceof Member){
			$this->member_obj = Member::getById($this->getMemberId());
        }
    	return $this->member_obj;
    }
    public function getMemberId() {
    	return $this->member;
    }
    public function getDate() {
        return date(Setting::getByName('date_format'),strtotime($this->date)); // TODO: timezone
    }
    public static function getAllowedResults(){
        return self::$allowed_game_results;
    }
    public function getGameSettings() {
    	if(is_null($this->game_settings)){
    		$this->game_settings = GameSetting::getByKey($this->getType());
    	}
    	return $this->game_settings;
    }
    /**
     * set a new date using an unix timestamp
     * 
     * @param int $date - unix timestamp
     * @param string $mode - default is 'unix'
     * 
     * @return boolean
     */
    public function setDate($date, $mode='unix') {
        if($mode == 'unix'){
            $date = date('Y-m-d H:i:s', $date);
        }else{
            return false;
        }
        $this->date = $date;
        return true;
    }
    
    
    /**
     * @todo make multiple result actions possible eg. cards and money
     * @param string $result
     * @throws ErrorException
     * @return mixed[] with keys type, text, cards
     */
    public function determineReward($result){
        
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
                    $cards = null;
                    try{
                    	$cards = Card::createRandomCards($this->getMember(), $result_amount);
                    }
                    catch (ErrorException $e){
                    	error_log(date('Y-m-d H:i:s').' - Game Result no cards created: '.$e->getMessage().PHP_EOL,3,ERROR_LOG);
                    }
                    // Tradelog
                    $cardnames = '';
                    if(is_array($cards)){
	                    foreach($cards as $card){
	                    		$cardnames.= $card->getName().' (#'.$card->getId().'), ';
	                    }
	                    $cardnames = substr($cardnames,0,-2);
                    }
                    Tradelog::addEntry($this->getMember(), 'game_won_log_text',$this->getGameSettings()->getName($this->getMember()->getLang()).' -> '.$cardnames);
                    
                    if(!is_array($cards) AND !is_null($cards)){
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
                    Tradelog::addEntry($this->getMember(), 'game_won_log_text',$this->getGameSettings()->getName($this->getMember()->getLang()).' -> '.$game_reward['money']);
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
        }
    }
    
}