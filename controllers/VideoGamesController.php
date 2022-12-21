<?php

namespace Controllers;


class VideoGamesController {
    
    public function formTopGames() {
        //realiser une requête à la base pour récuperer la liste des consoles
        //realiser une requête à la base pour récuperer la liste des jeux
        //actualiser les Seclect du HTML
        $model=new \Models\VideoGames();
        $consoles= $model->getAllConsoles();
        $playlists= $model->getPlayList();
        $id = $this->getId(); 
        
        $tops = $model->getOneTop($id);
        $numberOfVG = count($tops);
        $template = "addTopGames.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function submitAddGames(): void {
        
        $errors = [];
        $valids = [];
      
        if(array_key_exists('gamelist', $_POST) && array_key_exists('test', $_POST)
                && array_key_exists('content', $_POST) && array_key_exists('machine', $_POST))  {
     
            // l'utilisateur doit selectionner un jeu dans la liste
            if($_POST['gamelist'] == "vide") 
                $errors[] = "Veuillez selectionner un jeu dans la liste";
                
            if($_POST['machine'] == "vide") 
                $errors[] = "Veuillez selectionner une console dans la liste";
                
            // la note doit être inférieur ou égal à 20 mais supérieur ou égal à 0
            if(empty($_POST['test']) || $_POST['test'] > 20 || $_POST['test'] < 0)
                $errors[] =  'Veuillez renseigner une note entre 0 et 20 pour le jeu choisi !';

                if(count($errors) == 0) {
                    // On peut ajouter le jeu vidéo dans la base de donnée
                    if($_SESSION['user'] == true) {
                        
                        $userId  = $_SESSION['user']['id'];
                        $top = [
                                $_SESSION['user']['id'],
                                $_POST['gamelist'],
                                $_POST['content'],
                                $_POST['test'],
                                $_POST['machine']
                                ];
                        
                        //  var_dump($_POST['gamelist']);die;
                        $model = new \Models\VideoGames();
                        $gameTopsExist = $model->getOneTop($userId);
                        foreach($gameTopsExist as $gameTops)
                        //   var_dump($gameTops['id_game']);die;
        
                        if($_POST['gamelist'] == ($gameTops['id_game']))
                        $errors[] = "Erreur! Ce jeu existe déja dans votre Top";
                        
                        if(count($errors) == 0) {
                                
                            $model = new \Models\VideoGames();
                            $model->addTopGames($top);
                            $games = $model->getOneTop($userId);
                            $valids[] = 'Felicitation, le Jeu a été ajouté à votre Top';
                            $template = "displayTopsByUserCrud.phtml";
                            include_once 'views/layout.phtml';
                        }
                    }
                    if($_SESSION['admin'] == true) {
                        $top = [
                                $_SESSION['admin']['id'],
                                $_POST['gamelist'],
                                $_POST['content'],
                                $_POST['test'],
                                $_POST['machine']
                                ];
                        $userId  = $_SESSION['admin']['id'];        
                        $model = new \Models\VideoGames();
                        $gameTopsExist = $model->getOneTop($userId);
                        foreach($gameTopsExist as $gameTops)
                        
                        if($_POST['gamelist'] == ($gameTops['id_game']))
                        $errors[] = "Erreur! Ce jeu existe déja dans votre Top";
                        
                        if(count($errors) == 0) {
                            
                            $model = new \Models\VideoGames();
                            $model->addTopGames($top);
                            $games = $model->getOneTop($userId);
                            // var_dump($games);die;
                            $valids[] = 'Felicitation, le Jeu a été ajouté à votre Top';
                            $template = "displayTopsByUserCrud.phtml";
                            include_once 'views/layout.phtml';
                        }
                    }
                }
                // Si le formulaire n'est pas correctement rempli, on affiche les erreurs
                // Et on réalimente les "select" du formulaire
                $model=new \Models\VideoGames();
                $consoles= $model->getAllConsoles();
                $playlists= $model->getPlayList();
                $template = "addTopGames.phtml";
                include_once 'views/layout.phtml';
        }
    }
    
    public function updateTopGames()   {
        
        $errors = [];
        $valids = [];
    
        if (isset($_POST) && empty($_POST)) {
            $id = $this->getId();
            
            if($_SESSION['user'] == true) 
            $userId =  $_SESSION['user']['id'];
            
            if($_SESSION['admin'] == true) 
            $userId =  $_SESSION['admin']['id'];

            if ($id === false)   {
                $errors[] = 'le jeu n\'existe pas dans ton top ou dans le serveur';
                if($_SESSION['user'] == true) 
                $userId =  $_SESSION['user']['id'];
                // var_dump($userId);die;
                if($_SESSION['admin'] == true) 
                $userId =  $_SESSION['admin']['id'];
                $model = new \Models\VideoGames();
                $games = $model->getOneTop($userId);
                foreach ($games as $game)
                $template = "displayTopsByUserCrud.phtml";
                include_once 'views/layout.phtml';
            }  
                else {
                    
                    $model = new \Models\VideoGames();
                    $data = $model->getGameOfTopsUser($id, $userId);
                    //  var_dump($data['tops_id']);
                    if($data === false) {
                        
                        $errors[] = "le jeu n'existe pas dans ton top ou dans la bdd";
                        $model = new \Models\VideoGames();
                        $games = $model->getoneTop($userId);
                        foreach ($games as $game);
                        $template = "displayTopsByUserCrud.phtml";
                        include_once 'views/layout.phtml';
                    }
                        else  {
                            
                            $model = new \Models\VideoGames();
                            $consoles= $model->getAllConsoles();
                            $userTop = $data['tops_id'];
                            // var_dump($userTop);die;
                            $template = "formUpdateTopsGame.phtml";
                            include_once 'views/layout.phtml';
                        }
                }   
        } 
        else {
            
            if($_SESSION['user'] == true) 
                        $userId =  $_SESSION['user']['id'];
                 
            if($_SESSION['admin'] == true) 
                $userId =  $_SESSION['admin']['id'];  
                
            $userTop = strip_tags($_POST['id']);
            // var_dump($_POST['id']);die;
            if(isset($_POST) && !empty($_POST) && array_key_exists('test', $_POST) && array_key_exists('content', $_POST) 
                && array_key_exists('machine', $_POST)) {
                        
                // le commentaire ne doit pas être plus long que 800
                if(strlen($_POST['content']) > 800 )
                    $errors[] =  "Le commentaire est trop long, veuillez ne pas dépasser 800 caractères svp";
    
                // le test sur 20 doit être renseigné
                if(empty($_POST['test']) || $_POST['test'] > 20 || $_POST['test'] < 0)
                $errors[] =  'Veuillez renseigner une note entre 0 et 20 pour le jeu svp';

                if($_POST['machine'] == "vide") 
                $errors[] = "Veuillez selectionner une console dans la liste svp";

                if(count($errors) == 0) {
                    
                    // $userTop = strip_tags($_POST['id']);
                    $test = strip_tags($_POST['test']);
                    $content = strip_tags($_POST['content']);
                    $machine = strip_tags($_POST['machine']);
                    
                    //  var_dump($userTop);die;
                    
                    $model = new \Models\VideoGames();
                    $model->updateTopGame($userTop, $test, $content, $machine);
                
                    $games = $model->getOneTop($userId);
                    foreach ($games as $game)
                    $numberOfGames = count($games);
                    $valids[] = 'les modifications ont été faites avec succès';
                    $template = "displayTopsByUserCrud.phtml";
                    include_once 'views/layout.phtml';
                    exit;
                }
            }
            $model = new \Models\VideoGames();
            $topOfUser = $model->getOneTopById($userTop);
            $userId = $topOfUser[1];
            $id = $topOfUser[2];
            $data = $model->getGameOfTopsUser($id, $userId);
            // var_dump($topOfUser);
            $consoles= $model->getAllConsoles();
            $errors[] = 'Echec de la modification';
            $template = "formUpdateTopsGame.phtml";
            require 'views/layout.phtml';
        }   
    }

    public function deleteTopsGame(): void  {
        
        $id = $this->getId();
        // var_dump($id);
        if($_SESSION['user'] == true) 
        $userId =  $_SESSION['user']['id'];
        
        if($_SESSION['admin'] == true) 
        $userId =  $_SESSION['admin']['id'];
        //  var_dump($userId);die;
        
        if ($id === false)  {
            
            $errors[] = 'le jeu n\'existe pas dans la base de donnée';
            $model = new \Models\VideoGames();
            $games = $model->getOneTop($userId);
            foreach ($games as $game)
            $template = "displayTopsByUserCrud.phtml";
            include_once 'views/layout.phtml';
        } 
        else  {
    
                $model = new \Models\VideoGames();
                $data = $model->getGameOfTopsUser($id, $userId);
                // var_dump($data['tops_id']);die;
                $result = $model->deleteGameById($data['tops_id']);
                //   var_dump($result);
                $games = $model->getOneTop($userId);
                $numberOfGames = count($games);
                $valids[] = 'Felicitation, le jeu a bien été supprimé de votre TopGames';
                $template = "displayTopsByUserCrud.phtml";
                include_once 'views/layout.phtml';
        }
    }
    
    public function displayTopGames() {
        
        $model = new \Models\VideoGames();
        $games = $model->getAllTops();
        
        $template = "topGames.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function getId() : int|bool   {
        
        if (isset($_GET['road']) && isset($_GET['id']) && !empty($_GET['id'])) {
            $id = strip_tags($_GET['id']);
            return $id;
        //    var_dump($id);
        } 
        return false;
    }
    
    public function displayGameTops($id) {
        
        $model = new \Models\VideoGames();
        $games = $model->getOneGame($id);
        $comments = $model->getAllCommentsByGame($id);
        $template = "gameTops.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function displayTopsUser($id) {
    
        $id = $this->getId(); 
        $model = new \Models\VideoGames();
        $games = $model->getOneTop($id);
        foreach ($games as $game);
        $template = "topsUser.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function displayTopsUserCrud() {
        
        $id = $this->getId(); 
        $model = new \Models\VideoGames();
        $games = $model->getOneTop($id);
        foreach ($games as $game)
        
        require_once('config/config.php');
        $template = "displayTopsByUserCrud.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function displayTopByConsole() {
        
        $model = new \Models\VideoGames();
        $games = $model->getTopConsole();
        $template = "topConsole.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function getAjaxSearchGame() {

        // Récupérer ce que JS nous a envoyé
        $content = file_get_contents("php://input");
        $data = json_decode($content, true);
        
        $search = "%".$data['textToFind']."%";
        
        $model= new \Models\VideoGames();
        $games = $model->getSearchTopGames($search);
        
        $numberOfGames = count($games);
        
        // template qui va générer la partie html qui doit afficher l'article
        include 'views/searchAjax.phtml';
    }
    
        
}