<?php
/**
 * Admin Controller
 * 
 * @author NekoCari
 */


class AdminController extends AppController {
	
	public function __construct() {
		parent::__construct();
		$this->redirectNotLoggedIn();
	}
    
    /**
     * Team Dashboard
     */
    public function dashboard() {
        
        
        // check if user has required rights
        $user_rights = $this->login()->getUser()->getRights();
        if(!is_array($user_rights) OR count($user_rights) == 0 ){
            die($this->layout()->render('templates/error_rights.php'));
        }
        
        $this->layout()->render('admin/dashboard.php');
          
    }
    
    /**
     * Game Settings
     */
    public function games_index() {    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	//$this->auth()->setRequirements('rights', ['ManageGames']); // TODO: add new right?
    	$this->redirectNotAuthorized();
    	
    	if(isset($_POST['delete_game'])){
    		$game = GameSetting::getById($_POST['delete_game']);
    		if($game instanceof GameSetting){
    			if($game->delete()){
    				$this->layout()->addSystemMessage('success', 'admin_games_deleted');
    			}
    		}
    	}
    	
    	$list = new GameSettingList();
    	if(isset($_GET['order'], $_GET['direction'])){
    		$list->setOrder([$_GET['order']=>$_GET['direction']]);
    	}
    	if(isset($_GET['pg'])){
    		$list->setPage($_GET['pg']);
    	}
    	if(isset($_GET['search'])){
    		$list->setSearchStr($_GET['search']);
    	}
    	$data['list'] = $list;
    	
    	$this->layout()->render('admin/games/list.php',$data);
    }
    
    public function addGame() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	//$this->auth()->setRequirements('rights', ['ManageGames']); // TODO: add new right?
    	$this->redirectNotAuthorized();
    	
    	if(isset($_POST['addGame'])){
    		try{
    			$game_setting = new GameSetting();
    			$game_setting->setKey($_POST['game_key']);
    			$game_setting->setRouteIdentifier($_POST['route_identifier']);
    			$game_setting->setName($_POST['name']);
    			$game_setting->setDescription($_POST['description']);
    			$game_setting->setType($_POST['type']);
    			if(isset($_POST['daily_game']) AND $_POST['daily_game'] == 1){
    				$game_setting->setWaitTime(NULL);
    			}else{
    				$game_setting->setWaitTime($_POST['wait_time']);
    			}
    			$game_setting->create();
    			if($_POST['type'] == 'lucky'){
	    			// if is lucky create entry in db as well
	    			$lucky = new LuckyGame();
	    			$lucky->setSettingsId($game_setting->getId());
	    			$lucky->setChoiceType($_POST['lucky_choice_type']);
	    			$lucky->setChoices($_POST['lucky_choices']);
	    			$lucky->setResults($_POST['lucky_results']);
	    			$lucky->create();
    			}
    			
    			$this->layout()->addSystemMessage('success', 'admin_games_add_success');
    			$_GET['id']=$game_setting->getId();
    			$this->editGame();
    			die();
    		}
    		catch(ErrorException $e){
    			switch($e->getCode()){
    				case 4004:
    					$this->layout()->addSystemMessage('error', 'admin_game_result_value_invalid',[],$e->getMessage());
    					$lucky->create();
    					break;
    				case 4005:
    					$this->layout()->addSystemMessage('error', 'admin_game_choice_type_invalid',[],$e->getMessage());
    					$lucky->setChoiceType('text');
    					break;
    				case 4006:
    					$this->layout()->addSystemMessage('error', 'admin_game_to_few_results');
    					$lucky->setChoices(array());
    					$lucky->setResults(array());
    					break;
    				default:
    					throw $e;
    					break;
    			}
    			$lucky->create();
    			
    			$this->layout()->addSystemMessage('success', 'admin_games_add_success');
    			$_GET['id']=$game_setting->getId();
    			$this->editGame();
    			die();
    		}
    		catch(Exception $e){
    			$this->layout()->addSystemMessage('error', 'unknown_error');
    			error_log($e->getMessage().PHP_EOL,3,ERROR_LOG);
    		}
    	}
    	
    	$this->layout()->render('admin/games/add.php');
    }
    
    public function editGame() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	//$this->auth()->setRequirements('rights', ['ManageGames']); // TODO: add new right!
    	$this->redirectNotAuthorized();
    	
    	if(!isset($_GET['id']) OR !($game_setting = GameSetting::getById($_GET['id'])) instanceof GameSetting){
    		$this->redirectNotFound();
    	}
    	
    	
    	$data['choices'] = $data['results'] = '';
    	$lucky = LuckyGame::getByPk($game_setting->getId());
    	
    	if(isset($_POST['editGame']) ){
    		try{
	    		$game_setting->setRouteIdentifier($_POST['route_identifier']);
	    		$game_setting->setName($_POST['name']);
	    		$game_setting->setDescription($_POST['description']);
	    		if(isset($_POST['daily_game']) AND $_POST['daily_game'] == 1){
	    			$game_setting->setWaitTime(NULL);
	    		}else{
	    			$game_setting->setWaitTime($_POST['wait_time']);
	    		}
	    		$game_setting->update();
	    		
	    		if($game_setting->getType() == 'lucky'){
	    				$lucky->setChoiceType($_POST['lucky_choice_type']);
	    				$lucky->setChoices($_POST['lucky_choices']);
	    				$lucky->setResults($_POST['lucky_results']);
	    				$lucky->update();
	    		}
	    		
	    		$this->layout()->addSystemMessage('success', 'changes_saved');
    		}
    		catch(ErrorException $e){
    			switch($e->getCode()){
    				case 4004:
    					$this->layout()->addSystemMessage('error', 'admin_game_result_value_invalid',[],$e->getMessage());
    					break;
    				case 4005:
    					$this->layout()->addSystemMessage('error', 'admin_game_choice_type_invalid',[],$e->getMessage());
    					break;
    				case 4006:
    					$this->layout()->addSystemMessage('error', 'admin_game_to_few_results',[]);
    					break;
    				default:
    					throw $e;
    					break;
    			}
    		}
    		catch(Exception $e){
    			$this->layout()->addSystemMessage('error', 'unknown_error');
    			error_log($e->getMessage().PHP_EOL,3,ERROR_LOG);
    		}
    	}
    	
    	$data['game'] = $game_setting;
    	if($lucky instanceof LuckyGame ){
    		
    		$data['choice_type'] = $lucky->getChoiceType();
    		$choices = $lucky->getChoicesArr();
    		foreach($choices as $choice){
    			$data['choices'].= $choice.',';
    		}
    		$data['choices'] = substr($data['choices'],0,-1);
    		$results = $lucky->getResultsArr();
    		foreach($results as $result){
    			$data['results'].= $result.',';
    		}
    		$data['results'] = substr($data['results'],0,-1);
    	}
    	
    	$this->layout()->render('admin/games/edit.php',$data);
    }
    
    
    /**
     * Settings
     */
    public function settings() {    	
        // check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['EditSettings']);
    	$this->redirectNotAuthorized();
    	
        // update form was sent
        if(isset($_POST['updateSettings']) AND isset($_POST['settings'])){
            // go throug all settings and store the values in database
        	$error_str = '';
            $updated_str = '';
            foreach($_POST['settings'] as $name => $value){
                $setting = Setting::getByName($name);
                if($setting->getValue() != $value){
	                $setting->setValue($value);     
	                // check and log if any setting was not updated
	                if(!$setting->update()){
	                	$error_str.= $name.', ';
	                }else{
	                	$updated_str.= $name.', ';
	                }
                }
            }
            // display a success message
            if(strlen($updated_str) > 0){
            	$this->layout()->addSystemMessage('success','admin_settings_updated',[],substr($updated_str,0,-2));
            }
            // display a error message
            if(strlen($error_str) > 0){
            	$this->layout()->addSystemMessage('error','admin_settings_not_updated',[],substr($error_str,0,-2));
            }   
        }
                     
        $this->layout()->render('admin/settings.php',['settings'=>Setting::getAll(['name'=>'ASC'])]);          
    }
    
    /**
     * Routing
     */
    public function routes() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
    	
    	if(isset($_POST['action']) AND $_POST['action'] == 'del_route' AND isset($_POST['id']) AND ($route = Route::getByIdentifier($_POST['id'])) instanceof Route){
    		if($route->isDeletable()){
	    		if($route->delete()){
	    			$this->layout()->addSystemMessage('success', 'del_route_success');
	    		}else{
	    			$this->layout()->addSystemMessage('error', 'del_route_error');
	    		}
    		}else{
    			$this->layout()->addSystemMessage('error', 'del_route_denied');
    		}
    	}
    	
    	$list = new RouteList();
    	if(isset($_GET['order'], $_GET['direction'])){
    		$list->setOrder([$_GET['order']=>$_GET['direction']]);
    	}
    	if(isset($_GET['pg'])){
    		$list->setPage($_GET['pg']);
    	}
    	if(isset($_GET['search'])){
    		$list->setSearchStr($_GET['search']);
    	}
    	
    	$data['list'] = $list;
    	
    	$this->layout()->render('admin/routes/index.php',$data);    	
    }
    
    public function addRoute() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
    	
    	if(isset($_POST['addRoute'])){
    		$route = new Route();
    		$route->setPropValues($_POST);
    		if(!is_null($route->create())){
    			header("Location: ".BASE_URI.Routes::getUri('admin_routes_edit')."?new=true&id=".$route->getIdentifier());
    		}
    	}
    	
    	$this->layout()->render('admin/routes/add.php');
    	
    }
    
    public function editRoute() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
    	
    	if(isset($_GET['new'])){
    		$this->layout()->addSystemMessage('success', 'add_route_success');
    	}
    	
    	if(isset($_GET['id']) AND ($route = Route::getByIdentifier($_GET['id'])) instanceof Route){
    		if(isset($_POST['editRoute'])){
    			try{
    				$route->setPropValues($_POST);
	    			if($route->update()){
	    				$this->layout()->addSystemMessage('success', 'changes_saved');
	    			}
    			}
    			catch(ErrorException $e){
    				$this->layout()->addSystemMessage('error','0',[],$e->getMessage());
    			}
    		}
    		$this->layout()->render('admin/routes/edit.php',['route'=>$route]);
    	}else{
    		$this->redirectNotFound();
    	}
    }
    
    /**
     * Card Status (Card Manager Categories) Management
     */
    public function cardStatus() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
    	
    	$card_status_arr = CardStatus::getAll(['position'=>'ASC']);
    	
    	if(!empty($_POST)){
    		try{
	    		foreach($card_status_arr as $card_status){
	    			if($card_status instanceof CardStatus){
		    			// update tradeable
		    			if(isset($_POST['tradeable'][$card_status->getId()])){
		    				$card_status->setTradeable(1);
		    			}else{
		    				$card_status->setTradeable(0);
		    			}
		    			// update visibility
		    			if(isset($_POST['public'][$card_status->getId()])){
		    				$card_status->setPublic(1);
		    			}else{
		    				$card_status->setPublic(0);
		    			}
		    			// update new
		    			if(isset($_POST['new']) AND $_POST['new'] == $card_status->getId() ){
		    				$card_status->setNew(1);
		    			}else{
		    				$card_status->setNew(0);
		    			}
		    			// update collections
		    			if(isset($_POST['collections']) AND $_POST['collections'] == $card_status->getId() ){
		    				$card_status->setCollections(1);
		    			}else{
		    				$card_status->setCollections(0);
		    			}
		    			// update name
		    			if(!empty($_POST['name'][$card_status->getId()])){
		    				$card_status->setName($_POST['name'][$card_status->getId()]);
		    			}
		    			// update position
		    			if(!empty($_POST['position'][$card_status->getId()])){
		    				$card_status->setPosition(intval($_POST['position'][$card_status->getId()]));
		    			}
		    			$card_status->update();
		    			$card_status_arr = CardStatus::getAll(['position'=>'ASC']);
	    			}
	    		}
    			$this->layout()->addSystemMessage('success','changes_saved');
    		}
    		catch(ErrorException $e){
    			$this->layout()->addSystemMessage('error','0',[],$e->getMessage());
    		}
    	}
    	
    	$this->layout()->render('admin/card_status/index.php',['categories'=>$card_status_arr]);
    	
    }
    
    
    public function addCardStatus() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
    	
    	if(isset($_POST['name'])){
    		$card_status = new CardStatus();
    		$card_status->setName($_POST['name']);
    		if($card_status->create()){
    			$this->redirect('admin_card_status_index');
    		}else{
    			$this->layout()->addSystemMessage('error', 'unknown_error');
    		}
    	}	    	
    	$this->layout()->render('admin/card_status/add.php');
    }
    
    
    public function deleteCardStatus() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
    	
    	if(isset($_GET['id']) AND ($card_status = CardStatus::getById($_GET['id'])) instanceof CardStatus){
    		try {
    			$card_status->delete();
    			$this->redirect('admin_card_status_index');
    		}
    		catch (PDOException $e){
    			switch($e->getCode()){
    				case 23000:
    					$this->layout()->addSystemMessage('error', 'unknown_error',[],'karten existieren in dieser kategorie');
    					break;
    				default: 
    					throw new ErrorException('unknown_error');
    					break;
    			}
    		}
    		catch (ErrorException $e) {
    			$this->layout()->addSystemMessage('error', 'unknown_error',[],$e->getMessage());
    		}	
    	}else{
    		$this->redirectNotFound();
    	}
    	$this->layout()->render('admin/error.php');
    }
    
    
    /**
     * Memberlist
     */
    public function memberlist() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageMembers','ManageRights']);
    	$this->redirectNotAuthorized();
    	
    	$list = new MemberList();
    	if(isset($_GET['order'], $_GET['direction'])){
    		$list->setOrder([$_GET['order']=>$_GET['direction']]);
    	}
    	if(isset($_GET['pg'])){
    		$list->setPage($_GET['pg']);
    	}
    	if(isset($_REQUEST['search'])){
    		$list->setSearchStr($_REQUEST['search']);
    	}
        $data['currency_name'] = Setting::getByName('currency_name')->getValue();
        $data['list'] = $list;
        
        $this->layout()->render('admin/members/list.php',$data);
        
    }
    
    public function searchMember() {
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageMembers','ManageRights']);
    	$this->redirectNotAuthorized();
    	
    	if(!isset($_POST['search'])){
    		$this->layout()->render('admin/members/search.php');
    		
    	}else{    		
    		$this->memberlist();
    	}
    	
    }
    
    public function editMember() {        
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageMembers']);
    	$this->redirectNotAuthorized();
            
        $memberdata = Member::getById($_GET['id']);
        
        if(isset($_POST['updateMemberdata'])){
            
            try {
                $memberdata->setName($_POST['Name']);
                $memberdata->setLevel($_POST['Level']);
                $memberdata->setMail($_POST['Mail']);
                $memberdata->setMoney($_POST['Money']);
                $memberdata->setInfoText($_POST['Text']);
                $memberdata->setStatus($_POST['Status']);
                $memberdata->update();
                
                $this->layout()->addSystemMessage('success', 'admin_edit_user_success');
            }
            catch(Exception $e){
            	$this->layout()->addSystemMessage('error', 'admin_edit_user_failed',[],' - '.$e->getMessage());
            }
            
        }
       
        if(isset($_POST['deleteMemberdata'])){
            
            if($memberdata->delete()){
            	$this->layout()->addSystemMessage('success', 'admin_delete_user_success');
                $memberdata = false;
            }else{                    
            	$this->layout()->addSystemMessage('error', 'admin_delete_user_failed');
            }
            
        }
        
        if($memberdata){
            $data['accepted_stati'] = Member::getAcceptedStati();
            $data['memberdata'] = $memberdata->getEditableData($this->login());
            
            $this->layout()->render('admin/members/edit.php',$data);
            
        }else{
            
            $data['errors'] = array('ID NOT FOUND');
            
            $this->layout()->render('admin/error.php',$data);
        }
    }
    
    
    
    // TODO: rework giftMoney() and giftCards() to be more DRY?
    
    public function giftMoney(){
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageMembers']);
    	$this->redirectNotAuthorized();

        // get current member data
        $data['member'] = $member = Member::getById($_GET['id']);
        if(!$member instanceof Member){
            header("Location: ".BASE_URI.Routes::getUri('not_found'));
        }
        
        // get currency name for later use in view and log text
        $data['currency_name'] = $currency_name = Setting::getByName('currency_name')->getValue();
        
        // if add money form as sent
        if(isset($_POST['addMoney']) and intval($_POST['addMoney']) and isset($_POST['text'])){
            
            // put together text for tradelog
            $log_code = 'admin_gift_money_log_text';
            $log_text = intval($_POST['addMoney']).' '.$currency_name.' ('.strip_tags($_POST['text']).')';
            
            // add money,update member data, tradelog, send a pm, and set success message
            if($member->addMoney(intval($_POST['addMoney']),$log_code,$log_text)){
                $member->update();
                // Log
                Tradelog::addEntry($member,$log_code, $log_text);
                
                // add a message for user
                Message::add(null, $member->getId(), SystemMessageTextHandler::getInstance()->getTextByCode($log_code,$member->getLang()).$log_text);
                
                $this->layout()->addSystemMessage('success','admin_gift_money_success',[]," ".intval($_POST['addMoney']).' '.$currency_name);
            }else{
                // set error message
            	$this->layout()->addSystemMessage('error','admin_gift_money_failed');
            }
        }
        
        $this->layout()->render('admin/members/gift_money.php',$data);
        
    }
        
    public function giftCards(){
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageMembers']);
    	$this->redirectNotAuthorized();
           
        // get current member data
        $data['member'] = $member = Member::getById($_GET['id']);
        
        // if add card form was sent
        if(isset($_POST['addCards']) and intval($_POST['addCards']) and isset($_POST['text'])){
            
            // create the set number of random cards
            $cards = Card::createrandomCards($member,intval($_POST['addCards']));
            
            // if cards were created 
            if(is_array($cards) AND count($cards) > 0){
                
                // add all cardnames into a string
                $cardnames = '';
                foreach($cards as $card){
                    $cardnames.= $card->getName().", ";
                }
                $cardnames = substr($cardnames, 0, -2);
                
                // create Tradelog Entry
                Tradelog::addEntry($member, 'admin_gift_cards_log_text', $cardnames.' ('.strip_tags($_POST['text']).')');
                // add a message for user
                $msg_text = SystemMessageTextHandler::getInstance()->getTextByCode('admin_gift_cards_log_text',$member->getLang());
                Message::add(null, $member->getId(), $msg_text.$cardnames);
                
                // set success message
                $this->layout()->addSystemMessage('success', 'admin_gift_cards_success', array(), $cardnames);
            
            // no cards were created
            }else{
            	
            	$this->layout()->addSystemMessage('error', 'admin_gift_cards_failed');
            
            }
        }
        
        $this->layout()->render('admin/members/gift_cards.php',$data);
        
    }
    
    public function manageRights() {
        // check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageRights']);
    	$this->redirectNotAuthorized();
        
            
        // form to add right was submitted
        if(isset($_POST['add']) AND isset($_POST['right_id'])){
            if($member = Member::getById($_GET['id'])){
                $member->addRight($_POST['right_id']);
            }
        }
        
        // form to remove right was submitted
        if(isset($_POST['remove']) AND isset($_POST['right_id'])){
            if($member = Member::getById($_GET['id'])){
                $member->removeRight($_POST['right_id']);
            }
        }
        
        try {
            $member = Member::getById($_GET['id']);
            $member_rights = array();
            foreach($member->getRights() as $right){
                $member_rights[] = $right;
            }
            // get all possible rights
            $data['rights'] = Right::getAll();
            // get all data + rights current member already has
            $data['member'] = $member;
            $data['member_rights'] = $member_rights;
            
            $this->layout()->render('admin/members/rights.php', $data);
            
        }
        catch(Exception $e) {
            $data['errors'][] = $e->getMessage();
            $this->layout()->render('admin/error.php',$data);
        }           
        
    }
    
    /*
     * SQL Imports
     */
    public function resetPassword(){
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
        
        if(isset($_GET['id']) AND ($member = Member::getById($_GET['id'])) instanceof Member){
            
            $data = array();
            $data['member'] = $member;
            
            if(isset($_POST['reset'])){
                try{
                    if($member->resetPassword()){
                    	$this->layout()->addSystemMessage('success', 'admin_pw_reset_success');
                    }else{
                    	$this->layout()->addSystemMessage('error', 'admin_pw_reset_failure');
                    }
                }
                catch(Exception $e){
                	$this->layout()->addSystemMessage('error', '0',[],$e->getMessage());
                }
            }
            
            $this->layout()->render('admin/members/reset_password.php',$data);
            
        }else{
            header("Location: ".BASE_URI.Routes::getUri('not_found'));
        }
        
    }
    
    // TODO: rework sys messages importing sql
    public function importSql(){
        
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->redirectNotAuthorized();
        
        $dir = PATH.'import/';
        $data = array();
        
        
        if(isset($_POST['delete'])){
            $file = $_POST['delete'];
            if(file_exists($dir.$file)){
                unlink($dir.$file);
                $data['_success'][] = '<p>Datei <b>'.$file.'</b> gelöscht</p>';
            }else{
                $data['_error'][] = '<p>Datei <b>'.$file.'</b> konnte nicht gelöscht werden. Datei nicht gefunden.</p>';
            }
        }
        
        if(isset($_POST['import'])){
            try{
                $file = $_POST['import'];
                $sql = file_get_contents($dir.$file);
                if($sql != false){
                    $db = Db::getInstance();
                    $req = $db->query($sql);
                    
                    $data['_success'][] = '<p>Datei <b>'.$file.'</b> eingelesen</p>';
                    $file = rename($dir.$file, $dir.'IMPORTED_'.$file);
                    
                    $req->closeCursor();
                }else{
                    $data['_error'][] = '<p>Datei <b>'.$file.'</b> nicht gefunden oder ist leer</p>';
                }
            }
            catch(PDOException $e){
                $data['_error'][] = '<p>Datei <i>'.$file.'</i> <b>nicht</b> eingelesen.<br>'.$e->getMessage().'</p>';
            }
        }
        
        
        // import sql
        foreach(scandir($dir) as $file){
            $fnarr = explode('.', $file);
            if(end($fnarr) == 'sql'){
                $data['files'][] = $file; 
            }
        }
        
        $this->layout()->render('admin/import/list.php', $data);
        
    }

}
?>