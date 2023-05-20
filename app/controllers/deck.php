<?php
/*
 * Controller for deck related pages
 * 
 * @author NekoCari
 */

class DeckController extends AppController{
	
	/**
	 * Decks Index
	 */
	public function index() {
		
		$data = array();
		$list = new CarddeckList();
		$list->setDeckStatus('public');
		
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
		
		$this->layout()->render('deck/list.php', $data);
	}
	
	/**
	 * upcoming Decks Index
	 */
	public function upcomingIndex() {
		
		if(Setting::getByName('cards_decks_upcoming_public')->getValue() != 1){
			$this->redirectNotFound();
		}
				
		$data = array();
		$list = new CarddeckList();
		$list->setDeckStatus('new');
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
		
		$this->layout()->render('deck/list.php', $data);
	}
    
    /**
     * Decks by Category
     */
    public function category() {
        
    	if(isset($_GET['id']) AND ($category = Category::getById($_GET['id'])) instanceof Category){
            
             $this->layout()->render('deck/category.php', ['category'=>$category]);
                
        }else{
            
            $this->redirectNotFound();
            
        }
        
    }
    
    /**
     * Individual Deck Page
     */
    public function deckpage() {
    	
    	if(Setting::getByName('cards_decks_public')->getValue() != 1){
    		$this->redirectNotLoggedIn();
    	}
        
        if(isset($_GET['id'])){
        	
            $deck = Carddeck::getById($_GET['id']);
            
            if($deck instanceof Carddeck){
            	if($deck->getStatus() == 'new' AND Setting::getByName('cards_decks_upcoming_public')->getValue() != 1){
            		$this->redirectNotFound();
            	}
            	
            	if(isset($_POST['vote'])){
            		try{
	            		if($deck->addVote($this->login())){
	            			$this->layout()->addSystemMessage('success', 'vote_submited');
	            		}else{
	            			$this->layout()->addSystemMessage('error', 'unknown_error');
	            		}
            		}
            		catch(PDOException $e){
            			if($e->getCode() != 23000){
            				throw $e;
            			}
            		}
            		catch(Exception $e){
            			error_log($e->getMessage().PHP_EOL,3,ERROR_LOG);
            			$this->layout()->addSystemMessage('error', 'unknown_error');
            		}
            	}
            	
            	if(isset($_POST['wishlist'])){
            		if($_POST['wishlist'] == 'add'){
            			try{
            				if(MemberWishlistEntry::add($this->login(), $deck)){
	            				$this->layout()->addSystemMessage('success', 'wishlist_add_success');
            				}else{
            					$this->layout()->addSystemMessage('error', 'unknown_error');
	            			}
            			}
            			catch(PDOException $e){
            				if($e->getCode() != 23000){
            					throw $e;
            				}
            			}
            		}elseif($_POST['wishlist'] == 'remove'){
            			$entries = MemberWishlistEntry::getWhere(['member_id'=>$this->login()->getUserId(),'deck_id'=>$deck->getId()]);
            			if(count($entries) == 1){
            				if($entries[0]->delete()){
            					$this->layout()->addSystemMessage('success', 'wishlist_del_success');
            				}
            			}
            		}
            	}
            	
            	$data = array();
            	$data['deck'] = $deck ;
                $this->layout()->render('deck/deckpage.php',$data);
                
            }
            
        }else{
        	$this->redirectNotFound();
        }
        
    }
    
    
    /**
     * Deck Upload
     */
    public function deckUpload() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->redirectNotAuthorized();
            
        $data['categories'] = Category::getALL();
        $data['deck_types'] = DeckType::getAll(['name'=>'ASC']); 
        $data['max_deck_size'] = DeckType::getMaxSize();
        
        if(isset($_POST['upload'],$_POST['name'],$_POST['deckname'],$_POST['subcategory'],$_POST['type'])){
            try{
				if(isset($_FILES) AND count($_FILES) > 0){ 
					// with files to upload
                	$upload = new CardUpload($_POST['name'], $_POST['deckname'], $_FILES, $this->login()->getUserId(), $_POST['subcategory'], $_POST['type'], $_POST['description']);
					if(($upload_status = $upload->store()) === true){    
						$this->layout()->addSystemMessage('success','deck_upload_success');
					}else{
						$this->layout()->addSystemMessage('error','deck_upload_failed',[],' - '.$upload_status);
					}
				}else{
					// add new deck record to db
					$pd = new Parsedown();
					$deck = new Carddeck();
					$deck->setPropValues(['deckname'=>$_POST['deckname'], 'name'=>$_POST['name'], 'creator'=>$this->login()->getUserId(), 'type_id'=>$_POST['type'], 'description'=>$_POST['description'], 'description_html'=>$pd->parse($_POST['description'])]);
					$deck->create();
					// add new link to subcategory
					$deck_subcat = new DeckSubcategory();
					$deck_subcat->setPropValues(['deck_id'=>$deck->getId(), 'subcategory_id'=>$_POST['subcategory']]);
					$deck_subcat->create();
					
					$this->layout()->addSystemMessage('success','deck_add_success');
				}                
                
            }
            catch(PDOException $e){
                switch($e->getCode()){
                	case 23000:
                		$this->layout()->addSystemMessage('error','admin_upload_duplicate_key');
                        break;
                	default:
                		$this->layout()->addSystemMessage('error',9999,[],'<br>'.$e->getMessage());
                        break;
                }
            }
            catch(Exception $e){
            	$this->layout()->addSystemMessage('error','0',[],'<br>'.$e->getMessage());
            }
            
        }
        
        $this->layout()->render('admin/deck/upload.php', $data);
        
    }
    
    /**
     * Admin Deck List
     */
    public function adminDeckList() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->redirectNotAuthorized();
        
    	$list = new CarddeckList();
    	$list->setOrder(['status'=>'ASC']);
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
        $data['badge_css']['new'] = 'badge-primary';
        $data['badge_css']['public'] = 'badge-secondary';
        
        $this->layout()->render('admin/deck/list.php',$data);
            
    }
    
    /**
     * Deck edit
     */
    public function deckEdit() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->redirectNotAuthorized();
            
        if(isset($_POST['updateDeckdata'],$_POST['id'])){
            
            $deck = Carddeck::getById($_POST['id']);
            if($deck instanceof Carddeck){
                try{
                    $deck->setPropValues($_POST);
                    $deck->update();
                    if($deck->getSubcategory()->getId() != $_POST['subcategory']){
                        $relation = DeckSubcategory::getByUniqueKey('deck_id', $deck->getId());
                        $relation->setPropValues(['subcategory_id'=>$_POST['subcategory']]);
                        $relation->update();
                    }
                    
                    $this->layout()->addSystemMessage('success','deck_edit_success');
                }
                catch(Exception $e){
                	$this->layout()->addSystemMessage('error','unknown_error',[], $e->getMessage());
                }
                
            }
            
        }
        
        $data['categories'] = Category::getALL();            
        $data['deck_types'] = Carddeck::getAcceptedTypes(); 
        $data['deckdata'] = Carddeck::getById($_GET['id']);
        $data['memberlist'] = Member::getAll(['name'=>'ASC']);
        
        $this->layout()->render('admin/deck/edit.php',$data);
        
    }
    
}