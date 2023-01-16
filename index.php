<?php

session_start();

require('config/config.php');


spl_autoload_register(function($class) {
    require_once lcfirst(str_replace('\\','/', $class)) . '.php';
});


if(array_key_exists('road', $_GET)) : 
    

    switch($_GET['road']) {
        
        case 'home': 
            
            $controller = new Controllers\HomeController();
            $controller -> display();
        break;
        
        case 'addUser':
            
            $controller = new Controllers\UsersController();
            $controller -> add();
        break;
        
        case 'connectUser':
            
            $controller = new Controllers\UsersController();
            $controller -> submitformConnect();
        break;
        
        case 'users':
            
            $controller = new Controllers\UsersController();
            $controller -> displayUsers();
        break;
        
        case 'formConnect':
            
            $controller = new Controllers\UsersController();
            $controller -> formConnect();
        break;
        
        case 'formUserRegister':
            
            $controller = new Controllers\UsersController();
            $controller -> formUserRegister();
        break;
        
        case 'disconnect':
            
            $controller = new Controllers\UsersController();
            $controller -> disconnectUser();
        break;
        
        case 'displayFormTopGames':

            $controller = new Controllers\VideoGamesController();
            $controller -> formTopGames();
        break;
        
        case 'searchAjax':
            
            $controller = new Controllers\VideoGamesController();
            $controller -> getAjaxSearchGame();
        break;
        
        case 'topVgames':
            
            $controller = new Controllers\VideoGamesController();
            $controller -> displayTopGames();
        break;
        
        case 'topgamesbydevice':
            
            $controller = new Controllers\VideoGamesController();
            $controller -> displayTopByConsole();
        break;
        
        case 'displayGameTops':
            if(array_key_exists('id', $_GET)) {
                $controller = new Controllers\VideoGamesController();
                $controller -> displayGameTops($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'displayTopsUser':
            if(array_key_exists('id', $_GET)) {
                $controller = new Controllers\VideoGamesController();
                $controller -> displayTopsUser($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'displayTopsUserCrud':
            if(array_key_exists('id', $_GET)) {
                $controller = new Controllers\VideoGamesController();
                $controller -> displayTopsUserCrud($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'addTopGames':

            $controller = new Controllers\VideoGamesController();
            $controller -> submitAddGames();
        break;
        
        case 'updateTopsGame':
            if(isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
                $controller = new Controllers\VideoGamesController();
                $controller -> updateTopGames();
            
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'deleteTopsGame':
            if(array_key_exists('id', $_GET)) {
                $controller = new Controllers\VideoGamesController();
                $controller -> deleteTopsGame($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        default:
            
            header('location: index.php?road=home');
            exit;
        break;
    }
    
else: 
    header('location: index.php?road=home');
    exit;
    
endif;



