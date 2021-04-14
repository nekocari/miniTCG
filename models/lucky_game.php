<?php
/**
 * 
 * Represents a LuckyGame
 * 
 * Player can choose between given elements and than a result is randomly assigned
 * The count of the result array $possible_results must match the count of the choices $choices
 * 
 * @author Cari
 * 
 *
 */

require_once PATH.'models/game.php';

class LuckyGame {
    
    private 
        $name,
        $description = '',
        $choice_type = 'text', 
        $choice_elements = array(), 
        $possible_results = array();
    private static 
        $allowed_choice_types = array('text','image');
    
    /**
     * Creat a new new Lucky Game 
     * @param String[] $choice_elements
     * @param String[] $possible_results - array defining possible results, possible values see $allowed_game_results of Game Class
     */
    public function __construct($name,$description, $choice_type, $choice_elements,$possible_results) { 
        if(count($choice_elements) == count($possible_results)){
            $this->choice_elements = $choice_elements;
            $this->possible_results = $possible_results;
            $this->name = $name;
            $this->description = $description;
            $this->choice_type = $choice_type;
        }else{
            throw new ErrorException('Choice and Result Count must match!');
        }
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getDescription() {
        return $this->description;
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
                return $this->choice_elements;
                break;
            case 'image':
                $elements = array();
                foreach($this->choice_elements as $element){
                    $elements[$element] = '<img src="'.$element.'">';
                }
                return $elements;
                break;
        }
    }
    
    public function getResult() {
        $result_key = mt_rand(0,count($this->possible_results)-1);
        return $this->possible_results[$result_key];
    }
    
    
}