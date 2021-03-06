<?php
/*
 * Controller for Update related pages
 */

require_once 'models/member.php';
require_once 'models/admin.php';
require_once 'models/update.php';
require_once 'models/update_deck.php';
require_once 'models/carddeck.php';
require_once 'models/card.php';
require_once 'helper/pagination.php';

class UpdateController {
    
    public function updates() {
        
        // if is not logged in redirect to sign in form
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        $data = array();
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
            OR in_array('ManageUpdates',$admin->getRights())){    
            
            // action: publish update
            if(isset($_GET['action'], $_GET['id']) AND $_GET['action'] == 'publish'){
                
                // try to update database and set msg for case of success or failure
                
                try{
                    $update = Update::getById($_GET['id']);
                    $update->publish();
                    $data['_success'][] = SystemMessages::getSystemMessageText('update_publish_success');
                }
                catch(Exception $e){
                    $data['_error'][] = SystemMessages::getSystemMessageText('update_publish_failed');
                    $data['_error'][] = $e->getMessage();
                }
                
            }
            
            // action: delete update
            if(isset($_GET['action'], $_GET['id']) AND $_GET['action'] == 'delete'){
                
                // try to update database and set msg for case of success or failure
                $update = Update::getById($_GET['id']);
                if($update->delete()){
                    $data['_success'][] = SystemMessages::getSystemMessageText('update_delete_success');
                }else{
                    $data['_error'][] = SystemMessages::getSystemMessageText('update_delete_failed');
                }
                
            }
            
            // set values for pagination
            if(isset($_GET['pg'])){
                $currPage = $_GET['pg'];
            }else{
                $currPage = 1;
            }
            
            // get all updates form database
            $updates = Update::getAll();
            
            // set up pagination and pass values on to view
            $pagination = new Pagination($updates, 20, $currPage, Routes::getUri('update_index'));
            $data['updates'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml();
            
            Layout::render('admin/update/list.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * add a new update
     */
    public function add() {
        
        // if is not logged in redirect to sign in form
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
            OR in_array('ManageUpdates',$admin->getRights())){  
            
            // form was submitted and decks were chosen
            if(isset($_POST['addUpdate'], $_POST['decks'])){
                
                // create the update in database
                $update = new Update();
                $update_id = $update->create();
                $update->setPropValues(['id'=>$update_id]);
                
                // if update was successfully created
                if($update instanceof Update){
                    
                    // link each chosen deck to update
                    foreach($_POST['decks'] as $deck_id){
                        $relation = $update->addDeck($deck_id);
                    }
                    
                    header("Location: ".BASE_URI.Routes::getUri('deck_update'));
                    
                }else{
                    
                    // set up error message
                    $data['_error'][] = SystemMessages::getSystemMessageText('update_new_failed');
                    
                }
                
            }elseif(isset($_POST['addUpdate'])){
                
                $data['_error'][] = SystemMessages::getSystemMessageText('update_no_deck');
                
            }
            
            // get all new decks not related to any update 
            $data['new_decks'] = Carddeck::getNotInUpdate();
            
            Layout::render('admin/update/add.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * edit existing update
     */
    public function edit() {
        
        // if not logged in redirect to sign in form
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // create a new instance of admin class
        $admin = new Admin($_SESSION['user']->id);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())
            OR in_array('ManageUpdates',$admin->getRights())){  
            
            // get update data
            $update = Update::getById($_GET['id']);
            
            // if form was submitted
            if(isset($_POST['editUpdate'], $_POST['id'])){
                
                // do check in case of decks are to be removed so update is not empty 
                if((isset($_POST['remove_decks']) AND count($_POST['remove_decks']) == count($update->getRelatedDecks()) )
                    AND (!isset($_POST['add_decks']) OR  count($_POST['add_decks']) == 0) ){
                    
                    // setup error message
                    $data['_error'][] = SystemMessages::getSystemMessageText('update_no_deck');
                    
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
            // ... in case of update is new
            if($update->getStatus() == 'new'){
                
                $data['curr_decks'] = $update->getRelatedDecks();
                $data['new_decks'] = Carddeck::getNotInUpdate();
            
            // .. in case update is already published
            }else{
                
                $data['_error'][] = SystemMessages::getSystemMessageText('update_edit_published');
                $data['curr_decks'] = array();
                $data['new_decks'] = array();
                
            }
            
            Layout::render('admin/update/edit.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * take update cards
     */
    public function take() {
        
        // if is not logged in redirect to sign in form
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // get current update data 
        $curr_update = Update::getLatest();
        // get current user data
        $user = Member::getById($_SESSION['user']->id);
        
        // update button visability is true by default
        $data['show_take_button'] = true;
        
        // check if update data are valid and if user did not take cards from this update
        if($curr_update instanceof Update AND !$user->gotUpdateCards($curr_update->getId())){
            
            // form was submitted
            if(isset($_POST['takeUpdateCards'])){
                
                // try to add a new entry to database for taken cards
                $update_cards = Card::takeCardsFromUpdate($_SESSION['user']->id, $curr_update->getId());
                
                // set up messages for success or faliure
                if(!is_array($update_cards)){
                    $data['_error'][] = SystemMessages::getSystemMessageText('update_take_failed');
                }else{
                    $data['_success'][] = SystemMessages::getSystemMessageText('update_take_success');
                    $data['show_take_button'] = false;
                }
                
            }
            
            // get all decks from current update
            $data['update_decks'] = Carddeck::getInUpdate($curr_update->getId());
        
            Layout::render('login/take_update_cards.php',$data);
            
        }else{
            
            Layout::render('login/no_new_update.php');
            
        }
    }
    
}