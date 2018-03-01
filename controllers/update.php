<?php
/*
 * Controller for Update related pages
 */
class UpdateController {
    
    public function updates() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if($admin->getRights() != NULL){
            require_once 'models/update.php';
            require_once 'helper/pagination.php';
            
            if(isset($_GET['action'], $_GET['id']) AND $_GET['action'] == 'publish'){
                $published = Update::publish($_GET['id']);
                if($published === true){
                    $data['_success'][] = 'Decks im Update wurden freigeschaltet';
                }else{
                    $data['_error'][] = 'Decks nicht freigeschaltet: '.$published;
                }
            }
            
            if(isset($_GET['action'], $_GET['id']) AND $_GET['action'] == 'delete'){
                $deleted = Update::delete($_GET['id']);
                if($deleted === true){
                    $data['_success'][] = 'Update wurde gelöscht';
                }else{
                    $data['_error'][] = 'Update kann nicht gelöscht werden: '.$deleted;
                }
            }
            
            if(isset($_GET['pg'])){
                $currPage = $_GET['pg'];
            }else{
                $currPage = 1;
            }
            
            $updates = Update::getAll();
            $pagination = new Pagination($updates, 20, $currPage, Routes::getUri('update_index'));
            
            $data['updates'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml();
            
            Layout::render('admin/update/list.php',$data);
        }else{
            Layout::render('templates/error_rights.php');
        }
    }
    
    public function add() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if($admin->getRights() != NULL){
            
            require_once 'models/carddeck.php';
            require_once 'models/update.php';
            
            if(isset($_POST['addUpdate'], $_POST['decks'])){
                $update = Update::create();
                if($update instanceof Update){
                    foreach($_POST['decks'] as $deck_id){
                        $relation = $update->addDeck($deck_id);
                        if($relation !== true){
                            echo $relation;
                        }
                    }
                    header("Location: ".BASE_URI.Routes::getUri('deck_update'));
                }else{
                    $data['_error'][] = 'Update nicht angelegt: '.$update;
                }
            }elseif(isset($_POST['addUpdate'])){
                $data['_error'][] = 'Es muss mindestens ein Deck ausgewählt werden.';
            }
            
            $data['new_decks'] = Carddeck::getNotInUpdate();
            
            Layout::render('admin/update/add.php',$data);
        }else{
            Layout::render('templates/error_rights.php');
        }
    }
    
    public function edit() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if($admin->getRights() != NULL){
            
            require_once 'models/carddeck.php';
            require_once 'models/update.php';
            
            $update = Update::getById($_GET['id']);
            
            // TODO: process Form Data
            if(isset($_POST['editUpdate'], $_POST['id'])){
                if((isset($_POST['remove_decks']) AND count($_POST['remove_decks']) == count($update->getDecks()) )
                    AND (!isset($_POST['add_decks']) OR  count($_POST['add_decks']) == 0) ){
                    $data['_error'][] = 'Es dürfen nicht alle Decks entfent werden.';
                }else{
                    if(isset($_POST['add_decks'])){
                        foreach($_POST['add_decks'] as $deck_id){
                            $update->addDeck($deck_id);
                        }
                    }
                    if(isset($_POST['remove_decks'])){
                        foreach($_POST['remove_decks'] as $deck_id){
                            $update->removeDeck($deck_id);
                        }
                    }
                }
            }
            
            if($update->getStatus() == 'new'){
            $data['curr_decks'] = $update->getDecks();
            $data['new_decks'] = Carddeck::getNotInUpdate();
            }else{
                $data['_error'][] = 'Update kann nicht mehr bearbeitet werden.';
                $data['curr_decks'] = array();
                $data['new_decks'] = array();
            }
            
            Layout::render('admin/update/edit.php',$data);
        }else{
            Layout::render('templates/error_rights.php');
        }
    }
    
}