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
                
        // get all entries
        $entries = Tradelog::getAllByMemberId($this->login()->getUser()->getId());
        
        // set up pagination
        $pagination = new Pagination($entries, 20, Routes::getUri('tradelog_member'));   
        /*
        if(isset($_GET[$pagination->getParameterName()])){
        	$pagination->setCurrPage($_GET[$pagination->getParameterName()]);
        }*/
        $data = array();
        $data['entries'] = $pagination->getElements();
        $data['pagination'] = $pagination->getPaginationHtml();
        
        $this->layout()->render('tradelog/entries_member.php',$data);
    }
    
}
?>