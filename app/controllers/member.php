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
    	
    	$data['members'] = Member::getGrouped('level',['name'=>'ASC']);
        $data['level'] = Level::getAll();
        
        $this->layout()->render('member/list.php',$data);
    }
    
    /**
     * get a member profil using get id
     */
    public function profil() {
        
    	if(Setting::getByName('members_profil_public')->getValue() != 1){
    		$this->redirectNotLoggedIn(); 
    	}
        if(!isset($_GET['id'])){  $this->redirectNotFound(); }
            
        
        // get user or redirect to error page
        $member = Member::getById(intval($_GET['id']));
        if(!$member instanceof Member){ $this->redirectNotFound(); }
        $cat_elements = $collections = [];
        $cardmanager = null;
        
        
        // check category for partial
        $accepted_stati = Card::getAcceptedStatiObj();
        if(isset($_GET['cat']) AND array_key_exists($_GET['cat'], $accepted_stati) ){
        	$cat = $accepted_stati[$_GET['cat']];
        }elseif(isset($_GET['cat']) AND $_GET['cat'] == 'master'){
        	$cat = 'master';
        }else{
        	$cat = CardStatus::getTradeable()[0];
        }
        
        // get elements for partial
        switch($cat){            
            case 'master':
            	$cat_elements = $member->getMasteredDecks(true);
            	$pagination = new Pagination($cat_elements, 30,$member->getProfilLink().'&cat=master');
            	$partial_uri = 'member/profil/master.php';
                break;
            default:
            	$cat_elements = $member->getProfilCardsByStatus($cat->getId(), true);            	
            	if(!$cat->isCollections()){
            		// not collections
            		$pagination = new Pagination($cat_elements, 30,$member->getProfilLink().'&cat='.$cat->getId());
            		if($this->login()->isLoggedIn()){
            			$cat_elements = NULL;
            			foreach($pagination->getElements() as $element){
            				$cat_elements[] = $element->flag($this->login()->getUserId());
            			}
            		}else{
            			$cat_elements = $pagination->getElements();
            		}
            		if($cat->isTradeable() AND $this->login()->isloggedIn() AND $member->getId() != $this->login()->getUser()->getId()){
            			$partial_uri = 'member/profil/tradeable.php';
            		}else{
            			$partial_uri = 'member/profil/not_tradeable.php';
            		}
            	}else{
            		// for collections
            		$partial_uri = 'member/profil/collect.php';
            		$cardmanager = new Cardmanager($member);
            		$cardmanager->setStatus($cat);
            		$collect_decks = $cardmanager->getCollectionDecks();
            		$pagination = new Pagination($collect_decks, 2, $member->getProfilLink().'&cat='.$cat->getId());
            		$collections = $pagination->getElements();
            	}
            	break;
        }
        
        $data = [
        		'member'=>$member,
        		'cat'=>$cat,
        		'cat_elements'=>$cat_elements,
        		'collections'=>$collections,
        		'partial_uri'=>$partial_uri,
        		'cardmanager'=>$cardmanager,
        		'pagination'=>$pagination->getPaginationHtml()
        ];
        
        $this->layout()->render('member/profil.php',$data);
          
    }
    
    
    
    /**
     * mastercards
     */
    public function mastercards() {
        
    	$this->redirectNotLoggedIn();        
        
        // Vars needed
        $order = 'ASC';
        $order_by = 'deckname';
        $grouped = true;
        
        // process post vars if exist
        if(isset($_GET['order_by'])){
            $order_by = $_GET['order_by'];
        }
        if(isset($_GET['order'])){
            $order = $_GET['order'];
        }elseif($order_by == 'date'){
            $grouped = false;
            $order = 'DESC';
        }
        
        // get mastered sets from database
        $masters = Master::getMasteredByMember($this->login()->getUserId(),$grouped,[$order_by=>$order]);
        
        // pagination
        $pagination = new Pagination($masters, 20, Routes::getUri('member_mastercards').'?order_by='.$order_by);
        $pagination->checkForCurrPage();
        
        // set vars accessable in view
        $data['mastered_decks'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        $data['curr_order'] = $order_by;
        
        // render page
        $this->layout()->render('member/mastercards.php',$data);
    }
    
    /**
     * wishlist
     */
    public function wishlist() {
    	
    	$this->redirectNotLoggedIn();
    	
    	if(isset($_POST['delete'])){
    		$wish = MemberWishlistEntry::getById();
    		if($wish instanceof MemberWishlistEntry){
    			if($wish->delete()){
    				$this->layout()->addSystemMessage('success', 'wishlist_deck_deleted');
    			}
    		}
    	}
    	
    	$list = new MemberWishlist();
    	$list->setMember($this->login()->getUser());
    	
    	$data['list'] = $list;
    	
    	$this->layout()->render('member/wishlist.php',$data);
    	
    }
    
}