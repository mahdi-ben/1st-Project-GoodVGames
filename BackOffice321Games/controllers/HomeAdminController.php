<?php 

namespace Controllers;

class HomeAdminController {
    
    public function display() {
        
        require_once('../config/config.php');
        $template = "home.phtml";
        include_once 'views/layout.phtml';
    }   
    
    public function getAdmin(): bool {
        
        if(isset($_SESSION['connected']) && $_SESSION['connected'] == true && isset($_SESSION['admin'])){
            
            return true;
        }   
        else {
            return false;
            $template = "home.phtml";
            include_once 'views/layout.phtml';
        }
    }

}