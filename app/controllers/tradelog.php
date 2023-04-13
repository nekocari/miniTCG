<?php
/*
 * Tradelog Controller
 * 
 * @author NekoCari
 */

class TradelogController extends AppController {
    
    /**
     * Tradlog enties
     */
    public function overview() {
        
        $this->redirectNotLoggedIn();
        
        // set values for pagination
        if(isset($_GET['pg']) AND intval($_GET['pg'] > 0)){
            $currPage = $_GET['pg'];
        }else{
            $currPage = 1;
        }
        
        // get all entries
        $entries = Tradelog::getAllByMemberId($this->login()->getUser()->getId());
        
        // set up pagination
        $pagination = new Pagination($entries, 20, $currPage, RoutesDb::getUri('tradelog_member'));        
        $data = array();
        $data['entries'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        $this->layout()->render('tradelog/entries_member.php',$data);
    }
    
}
?>