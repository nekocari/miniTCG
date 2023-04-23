<?php
/*
 * Controller for Update related pages
 * 
 * @author NekoCari
 */

class UpdateController extends AppController {
    
    public function updates() {
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageUpdates']);
    	$this->redirectNotAuthorized();
            
        // action: publish update
        if(isset($_GET['action'], $_GET['id']) AND $_GET['action'] == 'publish'){            
            try{
                $update = Update::getById($_GET['id']);
                $update->publish();
                $this->layout()->addSystemMessage('success','update_publish_success');
            }
            catch(Exception $e){
            	$this->layout()->addSystemMessage('error','update_publish_failed',[],$e->getMessage());
            }
        }
        
        // action: delete update
        if(isset($_GET['action'], $_GET['id']) AND $_GET['action'] == 'delete'){
        	try{
        		$update = Update::getById($_GET['id']);
        		$update->delete();
        		$this->layout()->addSystemMessage('success','update_delete_success');
        	}
        	catch(Exception $e){
        		$this->layout()->addSystemMessage('error','update_delete_failed',[],$e->getMessage());
        	}
        }
        
        // set values for pagination
        if(isset($_GET['pg'])){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // get all updates form database
        $updates = Update::getAll(['date'=>'DESC']);
        
        // set up pagination and pass values on to view
        $pagination = new Pagination($updates, 20, $currPage, Routes::getUri('update_index'));
        $data['updates'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        $this->layout()->render('admin/update/list.php',$data);
               
    }
    
    /**
     * add a new update
     */
    public function add() {
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageUpdates']);
    	$this->redirectNotAuthorized();
                    
        // form was submitted and decks were chosen
        if(isset($_POST['addUpdate'], $_POST['decks'])){
            
            // create the update in database
            $update = new Update();
            $update->create();
            
            // if update was successfully created
            if($update instanceof Update){
                
                // link each chosen deck to update
                foreach($_POST['decks'] as $deck_id){
                    $update->addDeck($deck_id);
                }
                header("Location: ".BASE_URI.Routes::getUri('deck_update'));
                
            }else{
            	$this->layout()->addSystemMessage('error','update_new_failed');
            }
            
        }elseif(isset($_POST['addUpdate'])){
        	$this->layout()->addSystemMessage('error','update_no_deck');
        }
                
        $this->layout()->render('admin/update/add.php',['new_decks'=>Carddeck::getNotInUpdate()]);
        
    }
    
    /**
     * edit existing update
     */
    public function edit() {
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageUpdates']);
    	$this->redirectNotAuthorized();
        
        // get update data
        $update = Update::getById($_GET['id']);
        
        // if form was submitted
        if(isset($_POST['editUpdate'], $_POST['id'])){
            
            // do check in case of decks are to be removed, so update is not empty 
            if((isset($_POST['remove_decks']) AND count($_POST['remove_decks']) == count($update->getRelatedDecks()) )
                AND (!isset($_POST['add_decks']) OR  count($_POST['add_decks']) == 0) ){
                
                // error message
                $this->layout()->addSystemMessage('error','update_no_deck');
                
            }else{
                
                // link all selected decks to update
                if(isset($_POST['add_decks'])){
                    foreach($_POST['add_decks'] as $deck_id){
                        $update->addDeck($deck_id);
                    }
                }
                
                // unlink all selected decks from update
                if(isset($_POST['remove_decks'])){
                    foreach($_POST['remove_decks'] as $deck_id){
                        $update->removeDeck($deck_id);
                    }
                }
            }
        }
        
        // set values that are to be passed on to view
        // ... in case of update is 
        if($update->getStatus() == 'public'){
            $this->layout()->addSystemMessage('info','update_edit_published');            
        }
        
        $this->layout()->render('admin/update/edit.php',['update'=>$update]);
        
    }
    
    /**
     * take update cards
     */
    public function take() {
        
        // if is not logged in redirect to sign in form
        $this->redirectNotLoggedIn();
        
        // get current update data 
        $curr_update = Update::getLatest();
        // get current user data
        $user = $this->login()->getUser();
        
        // update button visability is true by default
        $data['show_take_button'] = true;
        
        // check if update data are valid and if user did not take cards from this update
        if($curr_update instanceof Update AND !$user->gotUpdateCards($curr_update->getId())){
            
            // form was submitted
            if(isset($_POST['takeUpdateCards'])){
                
                // try to add a new entry to database for taken cards
                $update_cards = Card::takeCardsFromUpdate($this->login()->getUserId(), $curr_update->getId());
                
                // set up messages for success or faliure
                if(!is_array($update_cards)){
                    $this->layout()->addSystemMessage('error','update_take_failed');
                }else{
                    $this->layout()->addSystemMessage('success','update_take_success');
                    $data['show_take_button'] = false;
                }
                
            }
            
            // get all decks from current update
            $data['update_decks'] = Carddeck::getInUpdate($curr_update->getId());
        
            $this->layout()->render('update/take_update_cards.php',$data);
            
        }else{
            
            $this->layout()->render('update/no_new_update.php');
            
        }
    }
    
}