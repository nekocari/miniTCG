<?php
/**
 * helper class to paginate elements
 * 
 * @author Cari
 *
 */
class Pagination {
  	
	private $elements;
	private $totalItems;
	private $itemsPerPage;
	private $currPage;
	private $offset;
	private $link;
	private $parameterName;
	private $css_class = 'pagination-sm justify-content-center';


    public function __construct($elements, $itemsPerPage, $currPage, $link, $offset=2, $parameterName='pg') {
		$this->elements	    = $elements;
		$this->itemsPerPage	= $itemsPerPage;
		$this->currPage		= $currPage;
		$this->offset		= $offset;
		$this->link			= $link;
		$this->parameterName= $parameterName;
		$this->totalItems   = count($elements);
    }

    /**
     * get number of pages in total
     * @return int
     */
    private function getTotalPages() {
		$totalPages = ceil($this->totalItems / $this->itemsPerPage);
		return $totalPages; 
    }
    
    /**
     * checks if a previous page exists
     * @return boolean
     */
    private function hasPrev() {
    	$hasPrev = false;
    	if($this->currPage > 1 ){
    		$hasPrev = true;
    	}
    	return $hasPrev;
    }
    
    /**
     * checks if a next page exists
     * @return boolean
     */
    private function hasNext() {
    	$hasNext = false;
    	if($this->currPage < $this->getTotalPages() ){
    		$hasNext = true;
    	}
    	return $hasNext;
    }
    
    /**
     * returns all pages that will be linked based on the set construct values
     * @return int[]
     */
    private function getPages() {
    	$pages = array();
    	for($i = $this->getFirst(); $i <= $this->getLast(); $i++){
    		$pages[] = $i;
    	}
    	return $pages;
    }
    
    /**
     * returns the current page
     * @return int
     */
    public function getCurr() {
    	return $this->currPage;
	}
    
	/**
	 * returns the last page to be displayed
	 * @return int
	 */
    private function getLast() {
    	$last = max( 1, min( $this->getTotalPages(), max( ($this->offset * 2)+1 , ($this->currPage + $this->offset) ) )) ;
    	return $last;
    }
    
    /**
     * returns the first page to be displayed
     * @return mixed
     */
    private function getFirst() {
        $first =  max(1, min( ($this->getTotalPages() - $this->offset * 2)-1 , max( ($this->currPage - $this->offset), 1) ));
        return $first;
    }
    
    /**
     * returns the parameter string that will be added to the link
     * @return string
     */
    private function getParameter() {
        if(strpos($this->link, '?')){
            $param = '&'.$this->parameterName.'=';
        }else{
            $param = '?'.$this->parameterName.'=';
        }
        return $param;
    } 
    
    /**
     * returns html code that displays all available links
     * @return string
     */
	public function getPaginationHtml() {
		$html = '<ul class="pagination '.$this->css_class.'">';
		if($this->getCurr() > 2){
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.'">&laquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&laquo;</a></li>';
		}
		if($this->hasPrev()){
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.$this->getParameter().($this->getCurr()-1).'">&lsaquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&lsaquo;</a></li>';
		}
		
		foreach($this->getPages() as $page){
			if($this->currPage != $page){
			    $html.= '<li class="page-item"><a class="page-link" href="'.$this->link.$this->getParameter().$page.'">'.$page.'</a></li>';
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
			$html.= '<li class="page-item"><a class="page-link" href="'.$this->link.$this->getParameter().($this->getTotalPages()).'">&raquo;</a></li>';
		}else{
			$html.= '<li class="page-item disabled"><a class="page-link">&raquo;</a></li>';
		}
		$html.= '</ul>';
		return $html;
    }
    
    /**
     * returns elements of current page
     * @return array
     */
    public function getElements() {
        $first_element = max(0, $this->itemsPerPage * (intval($this->currPage) - 1) );
        return array_slice($this->elements,$first_element,$this->itemsPerPage,true);
    }
    
    /**
     * sets an adition to the css class of the pagination main element 
     * @param string $class
     */
    public function setCssClass($class){
        $this->css_class = $class;
    }
    
}
?>