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
        
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){            
            
            if(isset($_POST['action']) AND $_POST['id'] AND $_POST['action'] == 'del_news'){
                if(($return = News::delete($_POST['id'])) !== true){
                    Layout::render('admin/error.php',['errors'=>array($return)]);
                    die();
                }
            }
            
            if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
                $currPage = $_GET['pg'];
            }else{
                $currPage = 1;
            }
            
            $news = News::getAll();
            $pagination = new Pagination($news, 10, $currPage, Routes::getUri('news_index'));
            
            $data = array();
            $data['news'] = $pagination->getElements();
            $data['pagination'] = $pagination->getPaginationHtml();
            
            if(count($data['news']) == 0){
                $data['_error'][] = 'Ungültige Seite: Keine Elemente zum Anzeigen.';
            }
            
            Layout::render('admin/news/list.php',$data);
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * add new news entry
     */
    public function add() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            
            if(isset($_POST['addNews']) AND isset($_POST['title']) AND isset($_POST['text'])){
                
                if(($return = News::add($_POST['title'],$_POST['text'])) === true){
                    
                    header("Location: ".BASE_URI.Routes::getUri('news_index'));
                    
                }else{
                    
                    Layout::render('admin/error.php',['errors'=>array($return)]);
                    
                }
                
            }else{
                
                Layout::render('admin/news/add.php');
                
            }
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
    /**
     * edit news entry
     */
    public function edit() {
        
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights()) OR in_array('CardCreator',$admin->getRights())){
            
            if(isset($_POST['updateNews']) AND isset($_POST['title']) AND isset($_POST['text'])){
                
                if(($return = News::update($_POST['id'],$_POST['title'],$_POST['text'])) === true){
                    
                    header("Location: ".BASE_URI.Routes::getUri('news_index'));
                    
                }else{
                    
                    Layout::render('admin/error.php',['errors'=>array($return)]);
                    
                }
                
            }else{
                
                $data['entry'] = News::getById($_GET['id']);
                Layout::render('admin/news/edit.php', $data);
                
            }
            
        }else{
            
            Layout::render('templates/error_rights.php');
            
        }
    }
    
}
?>