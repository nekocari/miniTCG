<?php
/*
 * Level Controller
 * 
 * @author NekoCari
 */

class LevelController extends AppController {
    
    /**
     * Level overview
     */
    public function level() {
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageLevel']);
    	$this->redirectNotAuthorized();
        
        $data['level'] = Level::getAll();
        
        $this->layout()->render('admin/level/list.php',$data);
            
    }
    
    /**
     * Level add
     */
    public function addLevel() {
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageLevel']);
    	$this->redirectNotAuthorized();
            
        $data = array();
        
        if(isset($_POST['addLevel'])){
            
            try {
	            $level = Level::add($_POST['level'], $_POST['name'], $_POST['cards']);
	            
	            
	            if(!empty($_FILES['file']['name'])){
	            	if(CardUpload::uploadLevelBadge($level->getLevel(),$_FILES['file'])){
	            		$this->layout()->addSystemMessage('success','level_badge_upload_success');
	            	}else{
	            		$this->layout()->addSystemMessage('error','level_badge_upload_failed');
	            	}
	            }
	            
	            $this->redirect('level_index');
            }
            catch(Exception $e){
                if($e instanceof PDOException){
                	if($e->getCode() == 23000){
                		$this->layout()->addSystemMessage('error','level_add_failed',[],'duplicate_key');
                    }
                }else{
                	$this->layout()->addSystemMessage('error','level_add_failed');
                	error_log($e->getMessage.PHP_EOL,3,ERROR_LOG);
                }
            }
            
        }
        
        $this->layout()->render('admin/level/add.php',$data);
            
        
    }
    
    /**
     * Level edit
     */
    public function editLevel() {
    	
    	// check if user has required rights
    	$this->auth()->setRequirements('roles', ['Admin']);
    	$this->auth()->setRequirements('rights', ['ManageLevel']);
    	$this->redirectNotAuthorized();
            
        
        $data = array();
        $level = Level::getById($_GET['id']);
        
        
        if(isset($_POST['editLevel'])){
            
            try{
                $level->setPropValues(['level'=>$_POST['level'],'name'=>$_POST['name'],'cards'=>$_POST['cards']]);
                $level->update();
                
                $this->layout()->addSystemMessage('success','level_edit_success');
                
                if(!empty($_FILES['file']['name'])){
                	if(CardUpload::uploadLevelBadge($level->getLevel(),$_FILES['file'])){
                		$this->layout()->addSystemMessage('success','level_badge_upload_success');
                	}else{
                		$this->layout()->addSystemMessage('error','level_badge_upload_failed');
                	}
                }
            }
            
            catch (Exception $e){
                $this->layout()->addSystemMessage('error','level_edit_failed',[],$e->getMessage());
            }
            
        }
        
        $data['level'] = $level;
        $this->layout()->render('admin/level/edit.php',$data);
         
    }
    
}
?>