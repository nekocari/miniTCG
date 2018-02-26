<?php
/*
 * Categories and Subcategories Controller
 */

class CategoryController {
  
    
    /**
     * Categories List
     */
    public function categories() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        
        if(in_array('Admin',$admin->getRights())){
            require_once PATH.'models/subcategory.php';
            
            if(isset($_POST['action']) AND $_POST['id']){
                if($_POST['action'] == 'del_cat'){
                    $return = Category::delete($_POST['id']);
                }elseif($_POST['action'] == 'del_subcat'){
                    $return = Subcategory::delete($_POST['id']);
                }
                if($return !== true){
                    Layout::render('admin/error.php',['errors'=>array($return)]);
                    die();
                }
            }
            $data['categories'] = Category::getALL();
            foreach($data['categories'] as $category){
                $cat_id = $category->getId();
                $data['subcategories'][$cat_id] = Subcategory::getByCategory($cat_id);
            }
            
            Layout::render('admin/category/index.php',$data);
        }else{
            Layout::render('templates/error_rights.php');
        }
        
    }
    
    /**
     * new Category
     */
    public function addCategory() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if(in_array('Admin',$admin->getRights())){
            if(isset($_POST['addCategory']) AND isset($_POST['name'])){
                require_once PATH.'models/category.php';
                if(($return = Category::add($_POST['name'])) === true){
                    header("Location: ".BASE_URI."admin/categories.php");
                }else{
                    Layout::render('admin/error.php',['errors'=>array($return)]);
                }
            }else{
                Layout::render('admin/category/add_category.php');
            }
        }else{
            Layout::render('templates/error_rights.php');
        }
        
    }
    
    /**
     * new Subcategory
     */
    public function addSubcategory() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if(in_array('Admin',$admin->getRights())){
            if(isset($_POST['addCategory']) AND isset($_POST['name']) AND isset($_POST['category'])){
                require_once PATH.'models/subcategory.php';
                if(($return = Subcategory::add($_POST['name'],$_POST['category'])) === true){
                    header("Location: ".BASE_URI."admin/categories.php");
                }else{
                    Layout::render('admin/error.php',['errors'=>array($return)]);
                }
            }else{
                require_once PATH.'models/category.php';
                $category = Category::getById($_POST['category']);
                Layout::render('admin/category/add_subcategory.php',['category'=>$category]);
            }
        }else{
            Layout::render('templates/error_rights.php');
        }
        
    }
    
    
    /**
     * edit Subcategory
     */
    public function editSubcategory() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if(in_array('Admin',$admin->getRights())){
            require_once PATH.'models/subcategory.php';
            $subcategory = Subcategory::getById($_GET['id']);
            if(isset($_POST['rename']) AND isset($_POST['name']) AND isset($_POST['category'])){
                $subcategory->setCategory($_POST['category']);
                $subcategory->setName($_POST['name']);
                $subcategory->store();
                header("Location: ".BASE_URI.Routes::getUri('category_index'));
            }
            $category = Category::getById($subcategory->getCategory());
            $categories = Category::getALL();
            Layout::render('admin/category/edit_subcategory.php',['category'=>$category,'subcategory'=>$subcategory,'categories'=>$categories]);
        }else{
            Layout::render('templates/error_rights.php');
        }
        
    }
    
    
    /**
     * edit Category
     */
    public function editCategory() {
        if(!isset($_SESSION['user'])){
            header("Location: ".BASE_URI.Routes::getUri('signin'));
        }
        require_once PATH.'models/admin.php';
        $admin = new Admin(Db::getInstance(),$_SESSION['user']);
        if(in_array('Admin',$admin->getRights())){
            require_once PATH.'models/category.php';
            $category = Category::getById($_GET['id']);
            if(isset($_POST['rename']) AND isset($_POST['name'])){
                $category->setName($_POST['name']);
                $category->store();
                header("Location: ".BASE_URI.Routes::getUri('category_edit'));
            }
            Layout::render('admin/category/edit_category.php',['category'=>$category]);
        }else{
            Layout::render('templates/error_rights.php');
        }
        
    }

}
?>