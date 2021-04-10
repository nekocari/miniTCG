<?php
/**
 * 
 * RepreseCnts a Card
 * 
 * @author Cari
 *
 */
require_once PATH.'models/card.php';

class CardCardmanager extends Card {
    
    protected $keep_flag, $collect_flag, $mastered_flag, $in_keep_flag, $in_collect_flag;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function missingInKeep(){
        if($this->missingInCollect() OR $this->keep_flag == 0 OR $this->in_keep_flag == 1){
            return false;
        }else{
            return true;
        }
    }
    
    public function missingInCollect(){
        if($this->collect_flag == 0 OR $this->in_collect_flag == 1){
            return false;
        }else{
            return true;
        }
    }
    public function deckInKeep(){
        if(!$this->keep_flag){
            return false;
        }else{
            return true;
        }
    }
    public function mastered(){
        if(!$this->mastered_flag){
            return false;
        }else{
            return true;
        }
    }
    
    public function getSortingOptions(){
        $options = array();
        $options[] = array('name'=>'','value'=>'','prop_selected'=>false);
        foreach(self::getAcceptedStati() as $status){
            $option = array();
            if($this->status != $status){
                switch($status){
                    case 'new': 
                        break;
                    case 'keep':
                        $option['name'] = $status;
                        $option['value'] = $status;
                        if($this->missingInKeep() AND $this->status == 'new'){
                            $option['prop_selected'] = true;
                        }else{
                            $option['prop_selected'] = false;
                        }
                        $options[] = $option;
                        break;
                    case 'collect':
                        if($this->in_collect_flag == 0){
                            $option['name'] = $status;
                            $option['value'] = $status;
                            if($this->missingInCollect() AND $this->status == 'new'){
                                $option['prop_selected'] = true;
                            }else{
                                $option['prop_selected'] = false;
                            }
                            $options[] = $option;
                        }
                        break;
                    case 'trade':
                        $option['name'] = $status;
                        $option['value'] = $status;
                        if(!$this->missingInCollect() AND !$this->missingInKeep() AND $this->status == 'new'){
                            $option['prop_selected'] = true;
                        }else{
                            $option['prop_selected'] = false;
                        }
                        $options[] = $option;
                        
                        break;
                    default;
                        $option['name'] = $status;
                        $option['value'] = $status;
                        $option['prop_selected'] = false;
                        $options[] = $option;
                        break;
                }
            }
        }
        return $options;
    }
    
    public function getSortingOptionsHTML(){
        $html = '';
        foreach($this->getSortingOptions() as $option){
            $html.= '<option value="'.$option['value'].'"';
            if($option['prop_selected']){
                $html.= ' selected';
            }
            $html.= '>'.$option['name'].'</option>\n';
        }
        return $html;
    }
    
}