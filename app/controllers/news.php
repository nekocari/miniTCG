<?php
/*
 * News Controller
 * 
 * @author NekoCari
 */

class NewsController extends AppController {
    
    /**
     * News overview
     */
    public function index() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageNews']);
    	$this->redirectNotAuthorized();
            
        if(isset($_POST['action']) AND $_POST['id'] AND $_POST['action'] == 'del_news'){
            $curr_news = News::getById($_POST['id']);
            if($curr_news instanceof News AND $curr_news->delete()){
            	$this->layout()->addSystemMessage('success', 'element_deleted');
            }else{
            	$this->layout()->addSystemMessage('error', 'news_delete_failed');
            }
        }
        
        $list = new NewsList();
        if(isset($_GET['order'], $_GET['direction'])){
        	$list->setOrder([$_GET['order']=>$_GET['direction']]);
        }
        if(isset($_GET['pg'])){
        	$list->setPage($_GET['pg']);
        }
        if(isset($_GET['search'])){
        	$list->setSearchStr($_GET['search']);
        }
        
        $currPage = 1; 
        if(isset($_GET['pg'])){
            $currPage = intval($_GET['pg']);
        }
        
        $news = News::getAll(['date'=>'DESC']);
        
        $pagination = new Pagination($news, 10, $currPage, Routes::getUri('news_index'));
        
        $data = array();
        $data['news'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        
        $data['list'] = $list;
        
        $this->layout()->render('admin/news/list.php',$data);
            
    }
    
    /**
     * add new news entry
     */
    public function add() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageNews']);
    	$this->redirectNotAuthorized();
            
        $data = array();    
            
        if(isset($_POST['addNews']) AND isset($_POST['title']) AND isset($_POST['text'])){
            
            if(News::add($this->login()->getUser(),$_POST['title'],$_POST['text']) instanceof News){
                
                header("Location: ".BASE_URI.Routes::getUri('news_index'));
                
            }else{
                $this->layout()->addSystemMessage('error','news_insert_failed');
            }
            
        }
        
        $this->layout()->render('admin/news/add.php',$data);
            
    }
    
    /**
     * edit news entry
     */
    public function edit() {
    	
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageNews']);
    	$this->redirectNotAuthorized();
            
        $data = array();
        
        if(isset($_POST['updateNews']) AND !empty($_POST['id']) AND !empty($_POST['title']) AND !empty($_POST['text'])){
            $curr_news = News::getById($_POST['id']);
            if($curr_news instanceof News){
                $curr_news->setPropValues(['title'=>$_POST['title'],'text'=>$_POST['text']]);
                if($curr_news->update()){
                    header("Location: ".BASE_URI.Routes::getUri('news_index'));
                }else{
                	$this->layout()->addSystemMessage('error','news_update_failed',[],' - database not updated');
                }
            }else{
            	$this->layout()->addSystemMessage('error','news_update_failed',[],' - failed to load news'); 
            }
            
        }elseif(isset($_POST['updateNews'])){
        	$this->layout()->addSystemMessage('error','news_update_failed',[],' - missing data'); 
        }
        
        $data['entry'] = News::getById($_GET['id']);
        $this->layout()->render('admin/news/edit.php', $data);
            
    }
    
    
    public function linkUpdate(){
       
    	$this->auth()->setRequirements('roles', ['Admin','CardCreator']);
    	$this->auth()->setRequirements('rights', ['ManageNews']);
    	$this->redirectNotAuthorized();
        
        // get current news entry
        if(!isset($_GET['id']) OR !$news = News::getById($_GET['id'])){
            $this->redirectNotFound();
        }
        
        $data = array();
        
        // process post data
        // add update
        if(isset($_POST['add'])){
            if($news->hasUpdate()){
                $news->removeUpdate($news->getRelatedUpdate()->getId());
            }
            if($news->addUpdate($_POST['add'])){
                // success
            }
        }
        
        // remove update
        if(isset($_POST['remove'])){
            if($news->removeUpdate($_POST['remove'])){
                // success
            }
        }
        
        // get currently linked 
        $data['linked_update'] = $news->getRelatedUpdate();
        $data['unlinked_updates'] = Update::getUnlinkedToNews();
        
        $this->layout()->render('admin/news/linked_update.php', $data);
    }
    
}
?>