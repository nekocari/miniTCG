<?php
class MyPagination {
  	
	private $totalItems, $link, $currPage;
	private $itemsPerPage = 20, $offset = 2, $parameterName = 'pg', $css_class = 'pagination-sm justify-content-center';


    public function __construct($total_items = 0, $uri = null) {
        $this->currPage = $this->setCurrPage();
        $this->link = $this->clearUri($uri);
		$this->totalItems = $total_items;
    }
    
    private function clearUri($uri){
        if(is_null($uri)){
            $uri = $_SERVER['REQUEST_URI'];
        }
        $uri = preg_replace('~((?<=\?)'.$this->parameterName.'=\d+&?|&'.$this->parameterName.'=\d+)~i','',$uri);
        if(substr($uri, strlen($uri)-1,1) == '?'){
            $uri = substr($uri,0,-1);
        }
        return $uri;
    }
    
    private function getExistingParameter() {
        $q_str = $_SERVER['QUERY_STRING'];
        $q_array = array();
        $q_expl = explode('&', $q_str);
        foreach($q_expl as $q_part){
            $q_item = explode('=',$q_part);
            $q_array[$q_item[0]] = $q_item[1];
        }
        return $q_array;
    }
    
    private function setCurrPage() {
        if(isset($_GET['pg']) AND is_numeric($_GET['pg']) AND $_GET['pg'] > 0){
            $pg = intval($_GET['pg']);
        }elseif(isset($_POST['pg']) AND is_numeric($_POST['pg']) AND $_POST['pg'] > 0){
            $pg = intval($_POST['pg']);
        }else{
            $pg = 1;
        }
        return $this->currPage = $pg;
    }
    
    public function setPage($pg){
        $this->currPage = intval($pg);
    }
    
    public function setCssClass($class) {
        $this->css_class = $class;
    }
    
    public function setOffset($offset) {
        $this->offset = intval($offset);
    }
    
    public function setTotalItems(int $total_items){
        $this->totalItems = $total_items;
    }
    
    public function setItemsPerPage($itemsPerPage) {
        $this->itemsPerPage = intval($itemsPerPage);
    }
    
    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }
    
    public function getLimitStart() {
        return max(0, $this->itemsPerPage * (intval($this->currPage) - 1) );
    }

    public function getTotalPages() {
		$totalPages = ceil($this->totalItems / $this->itemsPerPage);
		return $totalPages; 
    }
    
    public function hasPrev() {
    	$hasPrev = false;
    	if($this->currPage > 1 ){
    		$hasPrev = true;
    	}
    	return $hasPrev;
    }
    
    public function hasNext() {
    	$hasNext = false;
    	if($this->currPage < $this->getTotalPages() ){
    		$hasNext = true;
    	}
    	return $hasNext;
    }
    
    private function getPages() {
    	$pages = array();
    	for($i = $this->getFirst(); $i <= $this->getLast(); $i++){
    		$pages[] = $i;
    	}
    	return $pages;
    }
    
    public function getCurrPage() {
        return $this->currPage;
    }
    
    private function getLast() {
    	$last = max( 1, min( $this->getTotalPages(), max( ($this->offset * 2)+1 , ($this->currPage + $this->offset) ) )) ;
    	return $last;
    }
    
    private function getFirst() {
        $first =  max(1, min( ($this->getTotalPages() - $this->offset * 2)-1 , max( ($this->currPage - $this->offset), 1) ));
        return $first;
    }
    
    private function getParameter() {
        if(strpos($this->link, '?')){
            $param = '&'.$this->parameterName.'=';
        }else{
            $param = '?'.$this->parameterName.'=';
        }
        return $param;
    } 
    
    public function getPaginationHtml() {
        if($this->getTotalPages() > 1) {
    		$html = '<ul class="pagination '.$this->css_class.'">';
    		if($this->getCurrPage() > 2){
    			$html.= '<li class="page-item"><a class="page-link turn-page" data-action="first" data-pg="1" href="'.$this->link.'">&laquo;</a></li>';
    		}else{
    			$html.= '<li class="page-item disabled"><a class="page-link">&laquo;</a></li>';
    		}
    		if($this->hasPrev()){
    		    $html.= '<li class="page-item"><a class="page-link turn-page" data-action="prev" data-pg="'.($this->getCurrPage()-1).'" href="'.$this->link.$this->getParameter().($this->getCurrPage()-1).'">&lsaquo;</a></li>';
    		}else{
    			$html.= '<li class="page-item disabled"><a class="page-link">&lsaquo;</a></li>';
    		}
    		
    		foreach($this->getPages() as $page){
    			if($this->currPage != $page){
    			    $html.= '<li class="page-item"><a class="page-link" data-pg="'.$page.'" href="'.$this->link.$this->getParameter().$page.'">'.$page.'</a></li>';
    			}else{
    				$html.= '<li class="page-item active"><span class="page-link">'.$page.'</span></li>';
    			}
    		}
    	
    		if($this->hasNext()){
    		    $html.= '<li class="page-item"><a class="page-link turn-page" data-action="next" data-pg="'.($this->getCurrPage()+1).'" href="'.$this->link.$this->getParameter().($this->getCurrPage()+1).'">&rsaquo;</a></li>';
    		}else{
    			$html.= '<li class="page-item disabled"><a class="page-link">&rsaquo;</a></li>';
    		}
    		if($this->getTotalPages() > ($this->getCurrPage() + 1)){
    		    $html.= '<li class="page-item"><a class="page-link turn-page" data-action="last" data-pg="'.$this->getTotalPages().'" href="'.$this->link.$this->getParameter().($this->getTotalPages()).'">&raquo;</a></li>';
    		}else{
    			$html.= '<li class="page-item disabled"><a class="page-link">&raquo;</a></li>';
    		}
    		$html.= '</ul>';
    		return $html;
        }else{
            return null;
        }
    }
    
    public function getCurrPositionHtml($delemiter = '/', $text_before = null, $text_after = null) {
        $html = '<span class="pagination-current-position">';
        if(!is_null($text_before)){ $html.= $text_before.' '; }
        $html.= $this->getCurrPage().' '.$delemiter.' '.$this->getTotalPages();
        if(!is_null($text_after)){ $html.= ' '.$text_after; }
        $html.= '</span>';
        return $html;
    }
    
    public function getJumpHtml($option_prepend = null, $auto_submit = true, $css_class="form-select", $text_before = null, $text_after = null) {
        $html = '';
    
        if($this->getTotalPages() > 1){
            $html = '<form class="form-inline d-inline pagination-jump-to" 
                        method="get" action="'.$this->link.'"';
            if($auto_submit){
                $html.=' onchange="this.submit();"';
            }
            $html.= '>';
            foreach($this->getExistingParameter() as $key => $value){
                if($key!='uri' AND $key!=$this->parameterName){
                    $html.= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                }
            }
            $html.= '<select class="'.$css_class.'" name="'.$this->parameterName.'" >';
            if(!is_null($text_before)){ $html.= $text_before.' '; }
            for($i=1; $i<=$this->getTotalPages(); $i++){
                $html.= '<option value="'.$i.'"';
                if($this->getCurrPage() == $i){ $html.= ' selected'; }
                $html.='>'.$option_prepend.' '.$i.'</option>';
            }
            if(!is_null($text_after)){ $html.= ' '.$text_after; }
            $html.= '</select>';
            $html.= '</form>';
        }
        return $html;
    }
    
}
?>