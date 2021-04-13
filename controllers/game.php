<?php
/*
 * Game Controller
 */

require_once PATH.'models/game.php';
require_once PATH.'models/lucky_game.php';
require_once PATH.'games_settings.php';

class GameController {
    
    /**
     * game index
     */
    public function index() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }        
        
        $game_list = GAMES_SETTINGS;
        
        $data['games'] = array();
        
        foreach($game_list as $type => $entry){
             
            $entry_game = Game::getById($type, Login::getUser()->getId());
            
            if($entry_game instanceof Game and $entry_game->isPlayable()){
                
                $link = '<a href="'.Routes::getUri($entry['routing']).'">';
                $link.= SystemMessages::getSystemMessagetext('game_play_now');
                $link.= '</a>';
                
            }else{
                
                if(!$entry_game->isDailyGame()){
                    $link.= $entry_game->getMinutesToWait().' '.SystemMessages::getSystemMessagetext('game_waiting_minutes');
                }else{
                    $link = SystemMessages::getSystemMessagetext('game_waiting_tomorrow');
                }
                
            }
            
            $data['games'][] = array('name'=> $entry['name'], 'link'=>$link);
        }
        
        Layout::render('game/index.php',$data);
        
    }
    
    /**
     * lucky number
     */
    public function luckyNumber() {
        
        $game_key = 'lucky_number';
        $name = 'Glückszahl';
        $description = 'Wähle eine Zahl und mit Glück gewinnst du eine oder sogar zwei Karten!';
        $choice_type = 'text';
        $choice_elements = range(1,9);
        $possible_results = array('win-card:1','win-card:1','win-card:1','win-card:2','win-card:2','lost','lost','lost','lost');
        
        $this->lucky_game($game_key, $name, $description, $choice_type, $choice_elements, $possible_results);
       
    }
    
    /**
     * head or tail
     */
    public function headOrTail() {
        
        $game_key = 'head_or_tail';
        $name = 'Kopf oder Zahl';
        $description = 'Ich werfe eine Münze. Tippst das Ergebnis richtig, bekommst du eine Karte.';
        $choice_type = 'text';
        $choice_elements = array('Kopf','Zahl');
        $possible_results = array('win-card:1','lost');
        
        $this->lucky_game($game_key, $name, $description, $choice_type, $choice_elements, $possible_results);
        
    }
    
    /**
     * 
     * @param string $game_key - like set in game_settings.php
     * @param string $name - Angezeigter Name
     * @param string $description
     * @param string $choice_type
     * @param string[] $choice_elements
     * @param string[] $possible_results
     */
    private function lucky_game($game_key,$name, $description, $choice_type, $choice_elements, $possible_results){
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $game = Game::getById($game_key, Login::getUser()->getId());
        $lucky_game = new LuckyGame($name, $description, $choice_type, $choice_elements, $possible_results);
        
        if($game AND $game->isPlayable()){
            if(!isset($_POST['play'])){
                $data['game'] = $lucky_game;
                Layout::render('game/lucky_game.php',$data);
            }else{
                $data['game'] = $lucky_game;
                $data['reward'] = $game->determineReward($lucky_game);
                Layout::render('game/display_result.php',$data);
            }
        }else{
            Layout::render('game/wait_message.php');
        }
    }
    
}
?>