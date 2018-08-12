<?php
/*
 * Tradelog Controller
 */

class TradelogController {
    
    /**
     * Tradlog enties
     */
    public function overview() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once 'models/tradelog.php';
        require_once 'helper/pagination.php';
        
        if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        $entries = Tradelog::getAllByMemberId($_SESSION['user']->id);
        if(!$entries){ $entries = array(); }
        
        $pagination = new Pagination($entries, 20, $currPage, Routes::getUri('tradelog_member'));
        
        $data = array();
        $data['entries'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        Layout::render('tradelog/entries_member.php',$data);
    }
    
}
?>