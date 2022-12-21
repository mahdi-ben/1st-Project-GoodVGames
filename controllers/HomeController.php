<?php 

namespace Controllers;

class HomeController {
    
    public function display() {
        
        require_once('config/config.php');
        $template = "home.phtml";
        include_once 'views/layout.phtml';
    }   

}