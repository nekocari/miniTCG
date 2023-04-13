<?php
/*
 * Controller for member related pages
 * 
 * @author NekoCari
 */

class MemberController extends AppController {
    
    /**
     * get the memberlist
     */
    public function memberlist() {
        
        $data['members'] = Member::getGrouped('level','name','ASC');
        $data['level'] = Level::getAll();
        
        $this->layout()->render('member/list.php',$data);
    }
    
    /**
     * get a member profil using get id
     */
    public function profil() {
        
    	//$this->redirectNotLoggedIn(); // uncomment to make profils only accessable when logged in
        if(!isset($_GET['id'])){  $this->redirectNotFound(); }
            
        
        // get user or relocate to error page
        $data['member'] = Member::getById(intval($_GET['id']));
        if($data['member'] == false){ $this->redirectNotFound(); }
        
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
            case 'keep':
                $data['cat_elements'] = $data['member']->getProfilCardsByStatus($cat, $this->login()->getUserId());
                break;
                
            case 'collect':
            	$data['cat_elements'] = $data['member']->getProfilCardsByStatus($cat, $this->login()->getUserId());
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
        
        
        if($cat != 'collect'){
            $pagination = new Pagination($data['cat_elements'], 30, $currPage, $data['member']->getProfilLink().'&cat='.$cat);
            $data['cat_elements'] = $pagination->getElements();
        }else{
            $pagination = new Pagination($data['collections'], 4, $currPage, $data['member']->getProfilLink().'&cat='.$cat);
            $data['collections'] = $pagination->getElements();
        }
        $data['pagination'] = $pagination->getPaginationHtml();
        
        if($cat == 'trade' AND $this->login()->isloggedIn() AND $data['member']->getId() == $this->login()->getUser()->getId()){
            $data['partial_uri'] = 'member/profil/'.$cat.'_own.php';
        }else{
            $data['partial_uri'] = 'member/profil/'.$cat.'.php';
        }
        
        $this->layout()->render('member/profil.php',$data);
          
    }
    
    
    
    /**
     * mastercards
     */
    public function mastercards() {
        
    	$this->redirectNotLoggedIn();        
        
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
        $pagination = new Pagination($masters, 20, $curr_page, RoutesDb::getUri('member_mastercards').'?order_by='.$order_by);
        
        // set vars accessable in view
        $data['mastered_decks'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        $data['curr_order'] = $order_by;
        
        // render page
        $this->layout()->render('member/mastercards.php',$data);
    }
    
    
}