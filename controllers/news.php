<?php
/*
 * News Controller
 */

require_once 'models/admin.php';
require_once 'models/news.php';
require_once 'helper/pagination.php';

class NewsController {
    
    /**
     * News overview
     */
    public function index() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND 
            !in_array('CardCreator',$user_rights) AND 
            !in_array('ManageNews',$user_rights) ){
            die(Layout::render('templates/error_rights.php'));
        }         
            
        if(isset($_POST['action']) AND $_POST['id'] AND $_POST['action'] == 'del_news'){
            $curr_news = News::getById($_POST['id']);
            if($curr_news instanceof News AND $curr_news->delete()){
                $data['_success'][] = SystemMessages::getSystemMessageText('news_delete_success');
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('news_delete_failed');
            }
        }
        
        $currPage = 1; 
        if(isset($_GET['pg'])){
            $currPage = intval($_GET['pg']);
        }
        
        $news = News::getAll();
        
        $pagination = new Pagination($news, 10, $currPage, Routes::getUri('news_index'));
        
        $data = array();
        $data['news'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        if(count($data['news']) == 0){
            $data['_error'][] = 'Keine Elemente zum Anzeigen.';
        }
        
        Layout::render('admin/news/list.php',$data);
            
    }
    
    /**
     * add new news entry
     */
    public function add() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('CardCreator',$user_rights) AND
            !in_array('ManageNews',$user_rights) ){
                die(Layout::render('templates/error_rights.php'));
        }  
            
        $data = array();    
            
        if(isset($_POST['addNews']) AND isset($_POST['title']) AND isset($_POST['text'])){
            
            if(News::add($_POST['title'],$_POST['text']) instanceof News){
                
                header("Location: ".BASE_URI.Routes::getUri('news_index'));
                
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('news_insert_failed');
            }
            
        }
        
        Layout::render('admin/news/add.php',$data);
            
    }
    
    /**
     * edit news entry
     */
    public function edit() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        // check if user has required rights
        $user_rights = Login::getUser()->getRights();
        if(!in_array('Admin',$user_rights) AND
            !in_array('CardCreator',$user_rights) AND
            !in_array('ManageNews',$user_rights) ){
                die(Layout::render('templates/error_rights.php'));
        }  
            
        $data = array();
        
        if(isset($_POST['updateNews']) AND !empty($_POST['id']) AND !empty($_POST['title']) AND !empty($_POST['text'])){
            $curr_news = News::getById($_POST['id']);
            if($curr_news instanceof News){
                $curr_news->setPropValues(['title'=>$_POST['title'],'text'=>$_POST['text']]);
                if($curr_news->update()){
                    header("Location: ".BASE_URI.Routes::getUri('news_index'));
                }else{
                    $data['_error'][] = SystemMessages::getSystemMessageText('news_update_failed').' - database not updated';
                }
            }else{
                $data['_error'][] = SystemMessages::getSystemMessageText('news_update_failed').' - failed to load news'; 
            }
            
        }elseif(isset($_POST['updateNews'])){
            $data['_error'][] = SystemMessages::getSystemMessageText('news_update_failed').' - missing data'; 
        }
        
        $data['entry'] = News::getById($_GET['id']);
        Layout::render('admin/news/edit.php', $data);
            
    }
    
}
?>