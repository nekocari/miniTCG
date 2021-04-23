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
        
        if(!Login::loggedIn()){
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
                    $link = $entry_game->getMinutesToWait().' '.SystemMessages::getSystemMessagetext('game_waiting_minutes');
                }else{
                    $link = SystemMessages::getSystemMessagetext('game_waiting_tomorrow');
                }
                
            }
            
            $data['games'][] = array('name'=> $entry['name'], 'link'=>$link);
        }
        
        Layout::render('game/index.php',$data);
        
    }
    
    /**
     * default behavior of a lucky game
     * @param string $game_key - like set in game_settings.php
     * @param string $name - Angezeigter Name
     * @param string $description
     * @param string $choice_type
     * @param string[] $choice_elements
     * @param string[] $possible_results
     */
    private function lucky_game($game_key,$description, $choice_type, $choice_elements, $possible_results){
        // redirect if not logged in
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create the game object
        $game = Game::getById($game_key, Login::getUser()->getId());
        // create a lucky game object
        $lucky_game = new LuckyGame(GAMES_SETTINGS[$game_key]['name'], $description, $choice_type, $choice_elements, $possible_results);
        
        // check if a game object was created and the game is playable
        if($game instanceof Game AND $game->isPlayable()){
            
            // post request not sent?
            if(!isset($_POST['play'])){
                // display the default lucky game page
                $data['game'] = $lucky_game;
                Layout::render('game/lucky_game.php',$data);
            }else{
                // request was sent determine the reward
                $data['game_name'] = $lucky_game->getName();
                $data['reward'] = $game->determineReward($lucky_game->getResult());
                // display the result to the user, using the default game result page
                Layout::render('game/display_result.php',$data);
            }
        }else{
            Layout::render('game/wait_message.php');
        }
    }
    
    /**
     * trade in
     */
    public function tradeIn() {
        
        // redirect if not logged in
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create the game object
        $game_key = 'trade_in';
        $game_name = GAMES_SETTINGS[$game_key]['name'];
        $game = Game::getById($game_key, Login::getUser()->getId());
        
        // check if a game object was created and the game is playable
        if($game instanceof Game AND $game->isPlayable()){
            // post request not sent?
            if(!isset($_POST['play']) OR !isset($_POST['card'])){
                $data['game'] = $game;
                $data['cards'] = Login::getUser()->getDuplicateCards('trade');
                Layout::render('game/trade_in.php',$data);
            }else{ 
                // request was sent 
                $card = Card::getById($_POST['card']);
                if($card instanceof Card AND Login::getUser()->getId() == $card->getOwnerId()){
                    $card->delete();
                    Tradelog::addEntry(Login::getUser()->getId(), '[GAME] '.$game_name.' -> <strike>'.$card->getName().'</strike>');
                    $data['game_name'] = $game_name;
                    $data['reward'] = $game->determineReward('win-card:1');
                }else{
                    // user does not own card - redirect to start point
                    unset($_POST['card']);
                    $this->tradeIn();
                    exit;
                }
                
                // display the result to the user, using the default game result page
                Layout::render('game/display_result.php',$data);
            }
        }else{
            Layout::render('game/wait_message.php');
        }
        
    }
    
    /**
     * lucky number
     */
    public function luckyNumber() {
        
        $game_key = 'lucky_number'; // key as in /game_settings.php
        $description = 'Wähle eine Zahl und mit Glück gewinnst du eine oder sogar zwei Karten!'; // text for game detail page
        $choice_type = 'text'; // [text|image]
        $choice_elements = range(1,9); // user choices
        $possible_results = array('win-card:1','win-card:1','win-card:2','win-money:500','win-card:2','win-money:250','lost','lost','lost'); // possible results
        // NOTE: amount of choices and results needs to be the same!
        
        $this->lucky_game($game_key, $description, $choice_type, $choice_elements, $possible_results);
       
    }
    
    /**
     * head or tail
     */
    public function headOrTail() {
        
        $game_key = 'head_or_tail'; // key as in /game_settings.php
        $description = 'Ich werfe eine Münze. Tippst das Ergebnis richtig, bekommst du eine Karte.'; // text for game detail page
        $choice_type = 'text'; // [text|image]
        $choice_elements = array('Kopf','Zahl'); // user choices
        $possible_results = array('win-card:1','lost'); // possible results
        // NOTE: amount of choices and results needs to be the same!
        
        $this->lucky_game($game_key, $description, $choice_type, $choice_elements, $possible_results);
        
    }
    
    /*
     * add your own lucky game:
     * - copy and edit one of the example games and change the variable values
     * - add new route in /routing.php
     * - make sure you addes your game to /games_settings.php 
     */
    
}
?>