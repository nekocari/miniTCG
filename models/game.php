<?php
/**
 * 
 * Represents a Game
 * 
 * @author Cari
 *
 */
require_once PATH.'models/db_record_model.php';
require_once PATH.'models/member.php';
require_once PATH.'models/setting.php';
require_once PATH.'models/card.php';

class Game extends DbRecordModel {
    
    protected $id, $type, $member, $date, $wait_minutes;
    private $member_obj;
    
    protected static 
        $db_table = 'games',
        $db_pk = 'id',
        $db_fields = array('id','type','member','date'),
        $sql_order_by_allowed_values = array('id','type','member','date');
    
    private static 
        $allowed_game_results = array('win-card:1','win-card:2','win-card:3','lost'),
        $reward_texts = array(
            'lost'  => "Du hast leider nichts gewonnen.",
            'won'   => "Du hast <b>gewonnen!</b> <br>Hier dein Gewinn:");
    
    public function __construct() {
        parent::__construct();
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
            $game->setPropValues(array('wait_minutes'=>GAMES_SETTINGS[$type]['wait_minutes']));
        }else{
            $date = date('Y-m-d',time()-(60*60*24)).' 00:00:00';
            $game = new Game();
            $game->setPropValues(array('member'=>$member_id,'type'=>$type,'date'=>$date,'wait_minutes'=>GAMES_SETTINGS[$type]['wait_minutes']));
            $game_id = $game->create();
            $game->setPropValues(['id'=>$game_id]);
        }
        
        return $game;
    }
    
    /**
     * stores the current game data into the db
     * 
     * @throws ErrorException
     * @return boolean
     */
    public function store() {
        return parent::update();
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
        if($this->wait_minutes !== false){
            return false;
        }else{
            return true;
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
    public function getMember($mode = 'object') {
        if($mode == 'object'){
            if(!$this->member_obj instanceof Member){
                $this->member_obj = Member::getById($this->member);
            }
            return $this->member_obj;
        }elseif($mode == 'int'){
            return $this->member;
        }
    }
    public function getDate() {
        return date(Setting::getByName('date_format'),strtotime($this->date));
    }
    public static function getAllowedResults(){
        return self::$allowed_game_results;
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
    
    
    public function determineReward($curr_game){
        
        // get result code of the current specific game
        $result = $curr_game->getResult();
        
        // pattern for return value
        $game_reward = array('type'=>'','text'=>'','cards'=>array());
        
        if(in_array($result,self::$allowed_game_results)){
            
            switch($result){
            
                case 'win-card:1':
                case 'win-card:2':
                case 'win-card:3':
                    $game_reward['type'] = 'won';
                    $game_reward['text'] = self::$reward_texts['won'];
                    $cards_amount = intval(str_replace('win-card:','',$result));
                    $log_text = '[GAME] '.$curr_game->getName();
                    $cards = Card::createRandomCard($this->getMember('object')->getId(),$cards_amount,$log_text);
                    if(!is_array($cards)){
                        $game_reward['cards'][] = $cards;
                    }else{
                        $game_reward['cards'] = $cards;
                    }
                    break;
                    
                case 'lost':
                    $game_reward['type'] = 'lost';
                    $game_reward['text'] = self::$reward_texts['lost'];
                    break;
                    
                default:
                    return false;
                    break;
            }
            
            $this->setDate(time());
            $this->store();
            return $game_reward;
            
        }else{
            throw new ErrorException('Result is not an allowed value');
        }
    }
    
}