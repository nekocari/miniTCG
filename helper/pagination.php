<?php
class Pagination {
  	
	private $elements;
	private $totalItems;
	private $itemsPerPage;
	private $currPage;
	private $offset;
	private $link;
	private $css_class = 'pagination-sm justify-content-center';


    public function __construct($elements, $itemsPerPage, $currPage, $link, $offset=2) {
		$this->elements	    = $elements;
		$this->itemsPerPage	= $itemsPerPage;
		$this->currPage		= $currPage;
		$this->offset		= $offset;
		$this->link			= $link;
		$this->totalItems   = count($elements);
    }

    private function getTotalPages() {
		$totalPages = ceil($this->totalItems / $this->itemsPerPage);
		return $totalPages; 
    }
    
    private function hasPrev() {
    	$hasPrev = false;
    	if($this->currPage > 1 ){
    		$hasPrev = true;
    	}
    	return $hasPrev;
    }
    
    private function hasNext() {
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
    
    public function getCurr() {
    	return $this->currPage;
	}
    
    private function getLast() {
    	$last = min($this->getTotalPages(),max(($this->offset * 2)+1,($this->currPage + $this->offset)));
    	return $last;
    }
    
    private function getFirst() {
    	$first = max(($this->getTotalPages()-$this->offset * 2)-1,max(($this->currPage - $this->offset),1));
    	return $first;
    }    
    
	public function getPaginationHtml() {
		$html = '<ul class="pagination '.$this->css_class.'">';
		if($this->getCurr() > 2){
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.'">&laquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&laquo;</a></li>';
		}
		if($this->hasPrev()){
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.'?pg='.($this->getCurr()-1).'">&lsaquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&lsaquo;</a></li>';
		}
		
		foreach($this->getPages() as $page){
			if($this->currPage != $page){
				$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.'?pg='.$page.'">'.$page.'</a></li>';
			}else{
				$html.= '<li class="page-item active"><span class="page-link">'.$page.'</span></li>';
			}
		}
	
		if($this->hasNext()){
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.'?pg='.($this->getCurr()+1).'">&rsaquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&rsaquo;</a></li>';
		}
		if($this->getTotalPages() > ($this->getCurr() + 1)){
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.'?pg='.($this->getTotalPages()).'">&raquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&raquo;</a></li>';
		}
		$html.= '</ul>';
		return $html;
    }
    
    public function getElements() {
        $first_element = max(0, $this->itemsPerPage * (intval($this->currPage) - 1) );
        return array_slice($this->elements,$first_element,$this->itemsPerPage,true);
    }
    
    public function setCssClass($class){
        $this->css_class = $class;
    }
    
}
?>