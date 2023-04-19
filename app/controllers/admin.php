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
     * Settings
     */
    public function settings() {
    	
        // check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['EditSettings']);
    	$this->redirectNotAuthorized();
            
        
        $data = array();
        
        // update form was sent
        if(isset($_POST['updateSettings']) AND isset($_POST['settings'])){
            
            // go throug all settings and store the values in database
            foreach($_POST['settings'] as $name => $value){
                                    
                $setting = Setting::getByName($name);
                $setting->setValue($value);     
                
                // create error message in case of failure
                if(!$setting->update()){
                    $data['_errors'][] = $name." not updated";
                }
                
            }
            
        }
        
        // if form was sent and no error message was created - create a success message
        if(isset($_POST['updateSettings']) AND !isset($data['_error'])){ 
        	$this->layout()->addSystemMessage('success','admin_settings_updated');
        }else{
        	// TODO: display the errors
        }
        
        // get all settings form database
        $data['settings'] = Setting::getAll(['name'=>'ASC']);
        
        $this->layout()->render('admin/settings.php',$data);
          
    }
    
    /**
     * Routing
     */
    public function routes() {
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
    	$this->layout()->render('admin/routes/index.php',['routes'=>Route::getAll(['url'=>'ASC'])]);
    	
    }
    
    public function addRoute() {
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
            
        $members = Member::getAll(['id'=>'ASC']);
        $currPage = 1;
        
        if(isset($_GET['pg'])){ 
            $currPage = $_GET['pg']; 
        }
        
        $pagination = new Pagination($members, 10, $currPage, Routes::getUri('admin_member_index'));
        
        $data['members'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        $data['currency_name'] = Setting::getByName('currency_name')->getValue();
        
        $this->layout()->render('admin/members/list.php',$data);
        
    }
    
    /**
     * Member edit form
     */
    // TODO: add pw reset option
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
                
                $return = $memberdata->store();
                
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
            $data['memberdata'] = $memberdata->getEditableData('admin');
            
            $this->layout()->render('admin/members/edit.php',$data);
            
        }else{
            
            $data['errors'] = array('ID NOT FOUND');
            
            $this->layout()->render('admin/error.php',$data);
        }
    }
    
    /**
     * Member search form
     */
    public function searchMember() {   
        
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageMembers','ManageRights']);
    	$this->redirectNotAuthorized();
                
        if(!isset($_POST['search'])){
            
            $this->layout()->render('admin/members/search.php');
            
        }else{
            
            $data = array();
            $data['members'] = Member::searchByName($_POST['search']);
            $data['pagination'] = '';
            
            $this->layout()->render('admin/members/list.php',$data);
            
        }
        
    }
    
    
    // TODO: rework giftMoney() and giftCards() to be more DRY
    
    /**
     * Member Gift Money
     */
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
        
        
        
    /**
     * Member Gift Cards
     */
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
    
    /**
     * manage user rights 
     */
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