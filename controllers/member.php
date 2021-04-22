<?php
/*
 * Controller for member related pages
 */

require_once 'models/member.php';
require_once 'models/level.php';
require_once 'helper/pagination.php';

class MemberController {
    
    /**
     * get the memberlist
     */
    public function memberlist() {
        
        $data['members'] = Member::getGrouped('level','name','ASC');
        $data['level'] = Level::getAll();
        
        Layout::render('member/list.php',$data);
    }
    
    /**
     * get a member profil using get id
     */
    public function profil() {
        
        //if(!isset($_SESSION['user'])){ header('Location: '.BASE_URI.'error_login.php'); }
        if(!isset($_GET['id'])){  header('Location: '.BASE_URI.'error.php'); }
            
        
        // get user or relocate to error page
        $data['member'] = Member::getById(intval($_GET['id']));
        if($data['member'] == false){ header('Location: '.BASE_URI.'error.php'); }
        
        // current page for pagination
        if(isset($_GET['pg']) AND intval($_GET['pg'])>0 ){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // check category for partial
        if(isset($_GET['cat']) AND in_array($_GET['cat'], array('master','trade','keep','collect')) ){
            $cat = $_GET['cat'];
        }else{
            $cat = 'trade';
        }
        
        // get elements for partial
        switch($cat){
            
            case 'master':
                $data['cat_elements'] = $data['member']->getMasteredDecks(true);
                break;
                
            case 'trade':
                $data['cat_elements'] = $data['member']->getProfilCardsByStatus($cat);
                break;
            case 'keep':
                $data['cat_elements'] = $data['member']->getProfilCardsByStatus($cat);
                break;
                
            case 'collect':
                $data['cat_elements'] = $data['member']->getProfilCardsByStatus($cat);
                $data['collections'] = array();
                foreach($data['cat_elements'] as $card){
                    $data['collections'][$card->getDeckId()][$card->getNumber()] = $card;
                    if(!isset($data['deckdata'][$card->getDeckId()])){
                        $data['deckdata'][$card->getDeckId()] = Carddeck::getById($card->getDeckId());
                    }
                }
                $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
                $data['cards_per_row'] = Setting::getByName('deckpage_cards_per_row')->getValue();
                $data['searchcard_html'] = Card::getSearchcardHtml();
                $data['use_special_puzzle_filler'] = $use_special_puzzle_filler = Setting::getByName('card_filler_use_puzzle')->getValue();
                for($i = 1; $i <= Setting::getByName('cards_decksize')->getValue(); $i++){
                    if($use_special_puzzle_filler == 0){
                        $data['searchcard_html_'.$i] = $data['searchcard_html'];
                    }else{
                        $data['searchcard_html_'.$i] = Card::getSearchcardHtml('puzzle',$i);
                    }
                }
                break;
        }
        
        $pagination = new Pagination($data['cat_elements'], 35, $currPage, $data['member']->getProfilLink().'&cat='.$cat);
        $data['cat_elements'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        if($cat == 'trade' AND Login::loggedIn() AND $data['member']->getId() == Login::getUser()->getId()){
            $data['partial_uri'] = PATH.'views/member/profil/'.$cat.'_own.php';
        }else{
            $data['partial_uri'] = PATH.'views/member/profil/'.$cat.'.php';
        }
        
        Layout::render('member/profil.php',$data);
          
    }
    
    
    
    /**
     * mastercards
     */
    public function mastercards() {
        
        // if not logged in redirect
        if(!Login::loggedIn()){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        
        // Vars needed
        $curr_page = 1;
        $order = 'ASC';
        $order_by = 'deckname';
        $grouped = true;
        
        // process post vars if exist
        if(isset($_GET['pg']) AND intval($_GET['pg']) > 0){
            $curr_page = intval($_GET['pg']);
        }
        if(isset($_GET['order_by'])){
            $order_by = $_GET['order_by'];
        }
        if(isset($_GET['order'])){
            $order = $_GET['order'];
        }elseif($order_by == 'date'){
            $grouped = false;
            $order = 'DESC';
        }
        
        // get masterd sets from database
        $masters = Master::getMasterdByMember($_SESSION['user']->id,$grouped,[$order_by=>$order]);
        
        // pagination
        $pagination = new Pagination($masters, 20, $curr_page, Routes::getUri('member_mastercards').'?order_by='.$order_by);
        
        // set vars accessable in view
        $data['mastered_decks'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        $data['curr_order'] = $order_by;
        
        // render page
        Layout::render('member/mastercards.php',$data);
    }
    
    
}