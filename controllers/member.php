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
        
        if(!isset($_SESSION['user'])){ header('Location: '.BASE_URI.'error_login.php'); }
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
                $data['cat_elements'] = $data['member']->getMasteredDecks();
                break;
                
            case 'trade':
                $data['cat_elements'] = $data['member']->getCardsByStatus($cat,true);
                break;
            case 'keep':
                $data['cat_elements'] = $data['member']->getCardsByStatus($cat);
                break;
                
            case 'collect':
                $data['cat_elements'] = $data['member']->getCardsByStatus($cat);
                $data['collections'] = array();
                foreach($data['cat_elements'] as $card){
                    $data['collections'][$card->getDeckId()][$card->getNumber()] = $card;
                    if(!isset($data['deckdata'][$card->getDeckId()])){
                        $data['deckdata'][$card->getDeckId()] = Carddeck::getById($card->getDeckId());
                    }
                }
                $data['decksize'] = Setting::getByName('cards_decksize')->getValue();
                $data['cards_per_row'] = Setting::getByName('deckpage_cards_per_row')->getValue();
                $data['searchcard_html'] = Card::getSerachcardHtml();
                break;
        }
        
        $pagination = new Pagination($data['cat_elements'], 35, $currPage, $data['member']->getProfilLink().'&cat='.$cat);
        $data['cat_elements'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        $data['partial_uri'] = PATH.'views/member/profil/'.$cat.'.php';
        
        Layout::render('member/profil.php',$data);
          
    }
    
}