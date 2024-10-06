<?php
/*
 * Game Controller
 * 
 * @author NekoCari
 */


class GameController extends AppController {
    
    /**
     * game index
     */
    public function index() {
    	
    	$this->redirectNotLoggedIn();
    	$sys_msgs = SystemMessageTextHandler::getInstance();
        
        $game_list = GameSetting::getAll();
        
        $data['games'] = array();
        
        foreach($game_list as $entry){
        	
        	if($entry->isOnline()){
             
	            $entry_game = Game::getById($entry->getKey(), $this->login()->getUserId());
	            
	            if($entry_game instanceof Game and $entry_game->isPlayable()){
	            	$link_url = Routes::getUri($entry->getRouteIdentifier());
	            	// in case identifier points to default lucky game page add game id to url
	            	if($entry->getRouteIdentifier() == 'game_default_lucky'){
	            		$link_url.= '?id='.$entry->getId();
	            	}
	            	if($entry->getRouteIdentifier() == 'game_custom'){
	            		$link_url.= '?id='.GameCustom::getBySettingsId($entry->getId())->getId();
	            	}
	                $link = '<a href="'.$link_url.'">';
	                $link.= $sys_msgs->getTextByCode('game_play_now',$this->login()->getUser()->getLang());
	                $link.= '</a>';
	                
	            }else{
	                
	                if(!$entry->isDailyGame()){
	                	$link = $entry_game->getMinutesToWait().' '.$sys_msgs->getTextByCode('game_waiting_minutes',$this->login()->getUser()->getLang());
	                }else{
	                	$link = $sys_msgs->getTextByCode('game_waiting_tomorrow',$this->login()->getUser()->getLang());
	                }
	                
	            }
	            
	            $data['games'][] = array('name'=> $entry->getName($this->login()->getUser()->getLang()), 'link'=>$link);
	            
        	}
        	
        }
        
        $this->layout()->render('game/index.php',$data);
        
    }
    
    /**
     * default behavior of a lucky game
     */
    private function lucky_game($game_id){
    	
        // redirect if not logged in
        $this->redirectNotLoggedIn();
        
        $lucky_game = LuckyGame::getByPK($game_id);
        $lucky_game->setMember($this->login()->getUser());
        
        // check if a game object was created and the game is playable
        if($lucky_game->getMemberGame() instanceof Game AND $lucky_game->getMemberGame()->isPlayable()){
            
            // post request not sent?
            if(!isset($_POST['play'])){
                // display the default lucky game page
                $data['game'] = $lucky_game;
                $this->layout()->render('game/lucky_game.php',$data);
            }else{
                // request was sent determine the reward
            	$data['game_name'] = $lucky_game->getName($this->login()->getUser()->getLang());
            	$data['reward'] = $lucky_game->getMemberGame()->determineReward($lucky_game->getResult($_POST['choice']));
                // display the result to the user, using the default game result page
                $this->layout()->render('game/display_result.php',$data);
            }
        }else{
            $this->layout()->render('game/wait_message.php');
        }
    }
    
    
    
    /**
     * default for custom games (setup via app administration)
     */
    private function defaultGame($game_id){
    	if(!is_numeric($game_id) OR !($game_custom = GameCustom::getById($game_id)) instanceof GameCustom){
    		$this->redirectNotFound();
    	}
    	// fetch the settings
    	$game_setting = GameSetting::getById($game_custom->getSettingsId());
    	// fetch user specific game data
    	$game = Game::getById($game_setting->getKey(), $this->login()->getUserId());
    	
    	// check if a game object was created and the game is playable
    	if($game instanceof Game AND $game->isPlayable()){
    		
    		// check if a game result was send via post request
    		if(!isset($_POST['game_result'])){
    			// display the game
    			if(!empty($game_custom->getJsFilePath())){
    				$this->layout()->addJsFile($game_custom->getJsFilePath());
    			}
    			$this->layout()->render($game_custom->getViewFilePath(),['game'=>$game_setting]);
    			
    		}else{
    			$data['game_name'] = $game_setting->getName($this->login()->getUser()->getLang());
    			// process the game result
    			if(array_key_exists($_POST['game_result'], $game_custom->getResults())){
    				$data['reward'] = $game->determineReward($game_custom->getResults()[$_POST['game_result']]);
    			}else{
    				$data['reward'] = $game->determineReward('lost');
    			}
    			$this->layout()->render('game/display_result.php',$data);
    		}
    	}else{
    		// message in case game is not playable
    		$this->layout()->render('game/wait_message.php');
    	}
    }
    
    
    /**
     * trade in
     * @todo: refactor
     */
    public function tradeIn() {
    	
    	// redirect if not logged in
        $this->redirectNotLoggedIn();
        
        $game_setting = GameSetting::getByKey('trade_in');
        
        // create the game object
        $game = Game::getById($game_setting->getKey(), $this->login()->getUserId());
        
        // check if a game object was created and the game is playable
        if($game instanceof Game AND $game->isPlayable()){
            // post request not sent?
            if(!isset($_POST['play']) OR !isset($_POST['card'])){
                $data['game'] = $game;
                $data['cards'] = $this->login()->getUser()->getDuplicateCards('trade');
                $this->layout()->render('game/trade_in.php',$data);
            }else{ 
                // request was sent 
                $card = Card::getById($_POST['card']);
                if($card instanceof Card AND $this->login()->getUser()->getId() == $card->getOwnerId()){
                    $card->delete();
                    Tradelog::addEntry($this->login()->getUser(), 'game_loss_log_text', $game_setting->getName($this->login()->getUser()->getLang()).' -> <strike>'.$card->getName().'(#'.$card->getId().')</strike>');
                    $data['game_name'] = $game_setting->getName($this->login()->getUser()->getLang());
                    $data['reward'] = $game->determineReward('win-card:1');
                }else{
                    // user does not own card - redirect to start point
                    unset($_POST['card']);
                    $this->tradeIn();
                    exit;
                }
                
                // display the result to the user, using the default game result page
                $this->layout()->render('game/display_result.php',$data);
            }
        }else{
            $this->layout()->render('game/wait_message.php');
        }
        
    }
    
    public function rockPaperScissors(){
    	$this->defaultGame(2);
    	
    }
    
    public function hangman(){
    	$this->defaultGame(1);
    }
    
    
    /**
     * head or tail
     */
    public function headOrTail() {
    	$this->lucky_game(2);
    }
    /**
     * lucky number
     */
    public function luckyNumber() {
    	$this->lucky_game(1);
    }
    
    /**
     * default for lucky games!
     */
    public function customLucky() {
    	$this->lucky_game($_GET['id']);
    }
    
    /**
     * default for custom games (setup via app administration)
     */
    public function customGame(){
    	$this->defaultGame($_GET['id']);
    }
    
    
    
    // ------------ add your custom games below this line! --------------------------------------------
    // you might want to take a look at rockPaperScissors() to see how to incorperate a javascript game!
    
    
    
    
    
}
?>