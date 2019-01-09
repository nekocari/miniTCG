<?php
/*
 * Game Controller
 */

require_once 'models/game.php';
require_once 'models/lucky_game.php';

class GameController {
    
    /**
     * game index
     */
    public function index() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }        
        
        /* Hier ggf. weitere selbst erstellte Spiele einfügen!
         * type => Name des Spiels wie es in der "games" Tabelle der Datenbank geführt wird. WICHTIG!!
         * name => Name des Spiels wie es auf der Seite dargestellt wird.
         * routing => Gib die in der Routen Klasse zuvor festgelegte Kennung für die URL des Spiels an. 
         */
        $game_list = array(
            array('type' =>'lucky_number', 'name'=>'Glückszahl', 'routing'=>'game_lucky_number'),
            array('type' =>'head_or_tail', 'name'=>'Kopf oder Zahl', 'routing'=>'game_head_or_tail')
        );
        
        // ab hier nix mehr ändern ;)
        $data['games'] = array();
        
        foreach($game_list as $entry){
             
            $entry_game = Game::getById($entry['type'], $_SESSION['user']->id);
            
            if($entry_game and $entry_game->isPlayable()){
                
                $link = '<a href="'.Routes::getUri($entry['routing']).'">jetzt spielen</a>';
                
            }else{
                
                $link = 'nicht spielbar';
                
            }
            
            $data['games'][] = array('name'=> $entry['name'], 'link'=>$link);
        }
        
        Layout::render('game/index.php',$data);
        
    }
    
    /**
     * lucky number
     */
    public function luckyNumber() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $game = Game::getById('lucky_number', $_SESSION['user']->id);
        
        if($game AND $game->isPlayable()){
            
            $numbers = range(1,9); // erstellt ein array von allen zahlen von 1 bis 9
            $results = array('win-card:1','win-card:1','win-card:1','win-card:2','win-card:2','lost','lost','lost','lost');
            $lucky_game = new LuckyGame('Glückszahl','Wähle eine Zahl und mit Glück gewinnst du eine oder sogar zwei Karten!','text',$numbers,$results);
            
            if(!isset($_POST['play'])){
                
                $data['game'] = $lucky_game;
                
                Layout::render('game/lucky_game.php',$data);
                
            }else{
                
                $data['game'] = $lucky_game;
                $data['reward'] = $game->determineReward($lucky_game);
                $game->setDate(time());
                $game->store();
                
                Layout::render('game/display_result.php',$data);
                
            }
            
        }else{
            
            Layout::render('game/wait_message.php');
            
        }
    }
    
    /**
     * head or tail
     */
    public function headOrTail() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $game = Game::getById('head_or_tail', $_SESSION['user']->id);
        
        if($game AND $game->isPlayable()){
            
            $choices = array('Kopf','Zahl'); 
            $results = array('win-card:1','lost');
            $lucky_game = new LuckyGame('Kopf oder Zahl','Ich werfe eine Münze. Tippst das Ergebnis richtig, bekommst du eine Karte.','text', $choices, $results);
            
            if(!isset($_POST['play'])){
                
                $data['game'] = $lucky_game;
                
                Layout::render('game/lucky_game.php',$data);
                
            }else{
                
                $data['game'] = $lucky_game;
                $data['reward'] = $game->determineReward($lucky_game);
                $game->setDate(time());
                $game->store();
                
                Layout::render('game/display_result.php',$data);
                
            }
            
        }else{
            
            Layout::render('game/wait_message.php');
            
        }
    }
    
}
?>