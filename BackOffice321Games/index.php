<?php

session_start();
// echo password_hash("1234Abc&", PASSWORD_DEFAULT);


require('../config/config.php');


spl_autoload_register(function($class) {
    require_once lcfirst(str_replace('\\','/', $class)) . '.php';
});


if(array_key_exists('road', $_GET)) : 
    

    switch($_GET['road']) {
        
        case 'home': 
            
            $controller = new Controllers\HomeAdminController();
            $controller -> display();
        break;
        
        case 'formConnect':
   
            $controller = new Controllers\UsersAdminController();
            $controller -> formConnect();
        break;
        
        case 'connectAdmin':
            
            $controller = new Controllers\UsersAdminController();
            $controller -> submitFormConnect();
        break;
        
        case 'disconnect':
            
            $controller = new Controllers\UsersAdminController();
            $controller -> disconnectAdmin();
        break;
        
        case 'games':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> displayVideoGames();
            }  else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'formVideoGames':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> formVideoGames();
            }  else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'addGames':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> addVideoGames();
            }  else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'updateGames':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> updateGames();
            }  else {
                header('location: index.php');
                exit;
            }  
        break;
        
        case 'deleteGames':
            if(array_key_exists('id', $_GET)) {
            $controller = new Controllers\VideoGamesController();
            $controller -> deleteVideoGames($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'users':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\UsersAdminController();
                $controller -> displayUsers();
            }  else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'usersArray':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\UsersAdminController();
                $controller -> displayUsersArray();
            }  else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'formUsers':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\UsersAdminController();
                $controller -> formUsers();
            }  else {
                header('location: index.php');
                exit;
            }   
        break;
        
        case 'addUser':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\UsersAdminController();
                $controller -> addUser();
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'updateUser':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\UsersAdminController();
                $controller -> update();
            }  else {
                header('location: index.php');
                exit;
            }  
        break;
        
        case 'deleteUser':
            if(array_key_exists('id', $_GET)) {
            $controller = new Controllers\UsersAdminController();
            $controller -> deleteUser($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'topVgames':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> displayTopGames();
            } else {
                header('location: index.php');
                exit;
            } 
        break;
        
        case 'searchAjax':
            
            $controller = new Controllers\VideoGamesController();
            $controller -> getAjaxSearchGame();
        break;
        
        case 'displayFormTopGames':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> formTopVideoGames();
            }  else {
                header('location: index.php');
                exit;
            } 
        break;
        
        case 'addTopGames':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> submitAddGames();
            } else {
                header('location: index.php');
                exit;
            } 
        break;
            
        case 'displayAllComments':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin'])){
                $controller = new Controllers\VideoGamesController();
                $controller -> displayAllComments();
            } else {
                header('location: index.php');
                exit;
            }
        break;
        
        case 'deleteComment':
            if(isset($_SESSION['connected'])&& $_SESSION['connected'] == true && isset($_SESSION['admin']) && array_key_exists('id', $_GET)) {
            $controller = new Controllers\VideoGamesController();
            $controller -> deleteOneComment($_GET['id']);
            } else {
                header('location: index.php');
                exit;
            }
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
 
        default:
            
            header('location: index.php?road=formConnect');
            exit;
        break;
    }
    
else: 
    header('location: index.php?road=formConnect');
    exit;
    
endif;



