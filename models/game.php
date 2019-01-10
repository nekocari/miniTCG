<?php
/**
 * 
 * Represents a Game
 * 
 * @author Cari
 *
 */
require_once 'models/member.php';
require_once 'models/setting.php';
require_once 'models/card.php';

class Game {
    
    private $id, $type, $member, $date, $db;    
    private static 
        $allowed_game_results = array('win-card:1','win-card:2','win-card:3','lost'),
        $reward_texts = array(
            'lost'  => "Du hast leider nichts gewonnen.",
            'won'   => "Du hast <b>gewonnen!</b> <br>Hier dein Gewinn:");
    
    public function __construct($id, $type, $member, $date) {
        $this->id = $id;
        $this->type = $type;
        $this->member = $member;
        $this->date = $date;
        $this->db = Db::getInstance();
    }
    
    /**
     * get game datafrom database using game type and member id
     * 
     * @param string $type - Name of the game (like 'lucky_number')
     * @param int $member_id - Name of the game (like 'lucky_number')
     * 
     * @return boolean|Game
     */
    public static function getById($type, $member_id) {
        $game = false;
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT * FROM games WHERE member = :member_id AND type = :type');
        if($req->execute(array(':type' => $type, ':member_id' => $member_id))) {
            if($req->rowCount()) {
                $data = $req->fetch(PDO::FETCH_OBJ);
                $member = Member::getById($data->member);
                $game = new Game($data->id, $data->type, $member, $data->date);
            }else{
                $date = date('Y-m-d',time()-(60*60*24)).' 00:00:00';
                $req = $db->prepare('INSERT INTO games (member, type, date) VALUES(:member_id,:type,:date)');
                if($req->execute(array(':type' => $type, ':member_id' => $member_id, ':date' => $date))) {
                    $game = new Game($db->lastInsertId(), $type, $member_id, $date);
                }
            }
        }else{
            return false;
        }
        
        return $game;
    }
    
    /**
     * stores the current game data into the db
     * 
     * @throws ErrorException
     * @return boolean|string - returns either true or the string cotaining the error message
     */
    public function store() {
        try {
            $req = $this->db->prepare('UPDATE games SET type = :type, member = :member, date = :date WHERE id = :id');
            if($req->execute(array(':type' => $this->type, ':member' => $this->member->getId(), ':date'=>$this->date, ':id'=>$this->id))){
                return true;
            }else{
                throw new ErrorException('Datensatz konnte nicht aktualisiert werden.');
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * compare today with game date
     * 
     * checks if the currents games date lies before today
     * 
     * @return boolean
     */
    public function isPlayable() {
        // Datum heute 0 Uhr festlegen
        $today_str = date('Y-m-d').' 00:00:00';
        // unixzeitstempel vergleichen game muss kleiner sein als heute 
        if(strtotime($this->date) <= strtotime($today_str)){
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
    public function getMember() {
        return $this->member;
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
                    $cards = Card::createRandomCard($this->member->getId(),$cards_amount,$log_text);
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
            
            return $game_reward;
            
        }else{
            throw new ErrorException('Result is not an allowed value');
        }
    }
    
}