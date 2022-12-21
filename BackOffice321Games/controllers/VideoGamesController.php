<?php

namespace Controllers;


class VideoGamesController {
    
    public function displayVideoGames() {
        
        $model = new \Models\VideoGames();
        $games = $model->getAllVideoGames();
        $numberOfGames = count($games);
        require_once('../config/config.php');
        $template = "videoGamesCrud.phtml";
        include_once 'views/layout.phtml';
    } 
    
    public function formVideoGames() {
        
        $model=new \Models\VideoGames();
        $categoryOfGames= $model->getAllCategory();
        $pegiAge = $model->getAllPegi();
        $template = "formGames.phtml";
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
    
    public function addVideoGames() {
        
        $errors = [];
        $valids = [];
      
        if(array_key_exists('name', $_POST) && array_key_exists('descriptif', $_POST)
                && array_key_exists('multiplayerslocal', $_POST) && array_key_exists('online', $_POST)
                && array_key_exists('yearrelease', $_POST) && array_key_exists('price', $_POST)
                && array_key_exists('category_id', $_POST) && array_key_exists('pegi_id', $_POST)
                && array_key_exists('image', $_POST)) {
                
            //  le name doit contenir au moins 3 caractères
            if(strlen($_POST['name']) < 3) 
                $errors[] = "Veuillez renseigner au moins 3 caractères pour le nom";
                
            // le descriptif ne doit pas être plus long que 399
            if(strlen($_POST['descriptif']) > 399)
                $errors[] =  'Le descriptif est trop long';

            // le multiplayers local doit être renseigné
            if(empty($_POST['multiplayerslocal']))
                $errors[] = "Veuillez renseigner si c'est un jeu solo ou si un multiplayers en local est possible, si oui jusqu'à combien.";
                
            // le online doit être renseigné
            if(empty($_POST['online']))
                $errors[] = "Veuillez renseigner si le jeu en ligne est possible sur ce jeu, si oui jusqu'à combien de personnes.";
                
            //est-ce que l'année de sortie est cohérente  
            if($_POST['yearrelease'] < 1990 || $_POST['yearrelease'] > 2030) 
                $errors[] = "La date de sortie n'est pas cohérente";
            
            //est-ce que le prix est défini
            if(empty($_POST['price']) || ($_POST['price']) > 200) 
                $errors[] = "Le prix n'est pas défini en nombre ou est trop grand";
            
            if(empty($_POST['category_id']))
                $errors[] = "Veuillez choisir dans quelle categorie le jeu se situe";
                
            if(empty($_POST['pegi_id']))
                $errors[] = "Veuillez renseigner le PEGI du jeu ";    
               
            if(count($errors) == 0) {
                // Verifions si le jeu n'existe pas déjà dans notre bdd(via son name)
                $model = new \Models\VideoGames();
                $resultExist = $model->getGamesByName($_POST['name']);
            //    $resultGamesId = $model->getGamesById($_POST['id']);
                if(!empty($resultExist))
                    $errors[] = "Ce jeu existe déjà dans la Base de donnée";
            //    if(!empty($resultGamesId))
            //        $errors[] = "Ce jeu existe déjà, veuillez en choisir un autre svp";
        
                if(count($errors) == 0) {
                    // On peut ajouter l'utilisateur dans la base de donnée
                    // On nettoie les données envoyées
                    $name = strip_tags($_POST['name']);
                    $descriptif = strip_tags($_POST['descriptif']);
                    $multiplayerslocal = strip_tags($_POST['multiplayerslocal']);
                    $online = strip_tags($_POST['online']);
                    $yearrelease = strip_tags($_POST['yearrelease']);
                    $price = strip_tags($_POST['price']);
                    $category_id = strip_tags($_POST['category_id']);
                    $pegi_id = strip_tags($_POST['pegi_id']);
                    $image = strip_tags($_POST['image']);
                    
                    $model = new \Models\VideoGames();
                    $model->addGames($name, $descriptif, $multiplayerslocal, $online, $yearrelease, $price, $category_id, $pegi_id, $image);
                    $games = $model->getAllVideoGames();
                    $numberOfGames = count($games);
                    $valids[] = 'Felicitation, le nouveau jeu a bien été ajouté dans la bdd';
                    $template = "topGames.phtml";
                    include_once 'views/layout.phtml';
                }
            }    
            $model = new \Models\VideoGames();
            $categoryOfGames= $model->getAllCategory();
            $pegiAge = $model->getAllPegi();
            $template = "formGames.phtml";
            include_once 'views/layout.phtml';
        }
        
        $template = "formGames.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function updateGames()   {
        
        $errors = [];
        $valids = [];
        
        if (isset($_POST) && empty($_POST)) {
            $id = $this->getId();
            //  var_dump($id);
            if ($id === false)   {
                $errors[] = 'le jeu n\'existe pas dans nos serveurs';
                $model = new \Models\VideoGames();
                $games = $model->getAllVideoGames();
                $numberOfGames = count($games);
                $template = "videoGamesCrud.phtml";
                include_once 'views/layout.phtml';
            }   else {
                    $model = new \Models\VideoGames();
                    $data = $model->getGames($id);
    
                    if ($data === false)   {
                        $errors[] = "le jeu n'existe pas dans la bdd";
                        $model = new \Models\VideoGames();
                        $games = $model->getAllVideoGames();
                        $numberOfGames = count($games);
                        $template = "videoGamesCrud.phtml";
                        include_once 'views/layout.phtml';
                    }   else {
                        //    var_dump($data);
                            $model = new \Models\VideoGames();
                            $categoryOfGames= $model->getAllCategory();
                            $pegiAge = $model->getAllPegi();
                            $template = "formUpdateGames.phtml";
                            require 'views/layout.phtml';
                        }
                }
        } 
        else {
            
            if(array_key_exists('name', $_POST) && array_key_exists('descriptif', $_POST)
                        && array_key_exists('multiplayerslocal', $_POST) && array_key_exists('online', $_POST)
                        && array_key_exists('yearrelease', $_POST) && array_key_exists('price', $_POST)
                        && array_key_exists('category_id', $_POST) && array_key_exists('pegi_id', $_POST)
                        && array_key_exists('image', $_POST)) {

                // le nom du jeu doit contenir au moins 3 caractères
                if(strlen($_POST['name']) < 3) 
                    $errors[] = "Veuillez renseigner au moins 3 caractères pour le nom du Jeu";
                    
                // le descriptif ne doit pas être plus long que 399
                if(empty($_POST['descriptif']) || strlen($_POST['descriptif']) > 399 )
                    $errors[] =  "Le descriptif est trop long ou alors il est complètement vide";
    
                // le multiplayers local doit être renseigné
                if(empty($_POST['multiplayerslocal']))
                    $errors[] = "Veuillez renseigner si c'est un jeu solo ou si un multiplayers en local est possible, si oui jusqu'à combien.";
                    
                // le online doit être renseigné
                if(empty($_POST['online']))
                    $errors[] = "Veuillez renseigner si le jeu en ligne est possible sur ce jeu, si oui jusqu'à combien de personnes.";
                    
                //est-ce que l'année de sortie est cohérente  
                if($_POST['yearrelease'] < 1990 || $_POST['yearrelease'] > 2030) 
                    $errors[] = "La date de sortie n'est pas cohérente";
                
                //est-ce que le prix est défini
                if(empty($_POST['price']) || ($_POST['price']) > 200) 
                    $errors[] = "Le prix n'est pas défini en nombre ou est trop grand";
                
                if(empty($_POST['category_id']) || $_POST['category_id'] == "vide")
                    $errors[] = "Veuillez choisir dans quelle categorie le jeu se situe";
                    
                if(empty($_POST['pegi_id']) || $_POST['pegi_id'] == "vide")
                    $errors[] = "Veuillez renseigner le PEGI du jeu ";  
                
                if(empty($_POST['image']))
                    $errors[] = "Veuillez renseigner le nom du fichier image";

                if(count($errors) == 0) {
                    // On peut faire la modification dans la base de donnée
                    // On nettoie les données envoyées
                    $id = strip_tags($_POST['id']);
                    $name = strip_tags($_POST['name']);
                    $descriptif = strip_tags($_POST['descriptif']);
                    $multiplayerslocal = strip_tags($_POST['multiplayerslocal']);
                    $online = strip_tags($_POST['online']);
                    $yearrelease = strip_tags($_POST['yearrelease']);
                    $price = strip_tags($_POST['price']);
                    $category_id = strip_tags($_POST['category_id']);
                    $pegi_id = strip_tags($_POST['pegi_id']);
                    $image = strip_tags($_POST['image']);
                    
                    $model = new \Models\VideoGames();
                    $model->updateVideoGame($id, $name, $descriptif, $multiplayerslocal, $online, $yearrelease, $price, $category_id, $pegi_id, $image);
                    $games = $model->getAllVideoGames();
                    $numberOfGames = count($games);
             //       var_dump($result);
                    $valids[] = 'les modifications ont été faites avec succès';
                    $template = "topGames.phtml";
                    include_once 'views/layout.phtml';
                    exit;
                }
            }   
                $id = strip_tags($_POST['id']);
                $model = new \Models\VideoGames();
                $data = $model->getGames($id);
                $categoryOfGames= $model->getAllCategory();
                $pegiAge = $model->getAllPegi();
                $errors[] = 'Echec de la modification';
                $template = "formUpdateGames.phtml";
                require 'views/layout.phtml';
        }
    }
    
    public function deleteVideoGames(): void  {
        
        $users = [];
        $id = $this->getId();
        //  var_dump($id);
        
        if ($id === false)  {
            
            $model = new \Models\VideoGames();
            $games = $model->getAllVideoGames();
            $errors[] = 'le jeu n\'existe pas dans la bdd';
            $template = "topGames.phtml";
            include_once 'views/layout.phtml';
        } 
        else  {
            $model = new \Models\VideoGames();
            $result = $model->deleteGameById($id);
            //  var_dump($result);
            $games = $model->getAllVideoGames();
            $numberOfGames = count($games);
            $valids[] = 'Felicitation, le jeu a bien été supprimé';
            $template = "topGames.phtml";
            include_once 'views/layout.phtml';
        }
    }
    
    public function deleteOneComment(): void  {
        
        $id = $this->getId();
        //   var_dump($id);die;

        if ($id === false)  {
            
            $errors[] = "le commentaire n'existe pas ou ne peut pas être effacé";
            $model = new \Models\VideoGames();
            $comments = $model->getAllComments();
            $template = "displayAllComments.phtml";
            include_once 'views/layout.phtml';
        } 
        else  {
            $model = new \Models\VideoGames();
            $result = $model->deleteOneTopById($id);
            $comments = $model->getAllComments();
            $numberOfComments = count($comments);
            $valids[] = 'Felicitation, le commentaire et le jeu de ce Top ont bien été supprimé';
            $template = "displayAllComments.phtml";
            include_once 'views/layout.phtml';
        }
    }
    
    public function formTopVideoGames() {
        //realiser une requête à la base pour récuperer la liste des consoles
        //realiser une requête à la base pour récuperer la liste des jeux
        $model=new \Models\VideoGames();
        $playlists= $model->getAllVideoGames();
        $consoles= $model->getAllConsoles();
        $template = "addTopGames.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function submitAddGames(): void {
        
        $errors = [];
        $valids = [];
      
        if(array_key_exists('gamelist', $_POST) && array_key_exists('test', $_POST)
                && array_key_exists('content', $_POST)) {
      
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
                    //   var_dump($gameTops['id_game']);die;
        
                    if($_POST['gamelist'] == ($gameTops['id_game']))
                    $errors[] = "Erreur! Ce jeu existe déja dans votre Top";
                    
                    if(count($errors) == 0) {
                    $model = new \Models\VideoGames();
                    $model->addTopGames($top);
                    $valids[] = 'Felicitation, le Jeu a été ajouté à votre Top';
                    }
                }
                // Si le formulaire n'est pas correctement rempli, on affiche les erreurs
                // Et on réalimente les "select" du formulaire
                $model=new \Models\VideoGames();
                $playlists= $model->getAllVideoGames();
                $consoles= $model->getAllConsoles();
                $template = "addTopGames.phtml";
                include_once 'views/layout.phtml';
        }
        $template = "addTopGames.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function displayTopGames() {
        
        $model = new \Models\VideoGames();
        $games = $model->getAllVideoGames();
        $numberOfGames = count($games);
        $template = "topGames.phtml";
        include_once 'views/layout.phtml';
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
    
    public function displayAllComments() {
        
        $model = new \Models\VideoGames();
        $comments = $model->getAllComments();
        $numberOfComments = count($comments);
        $template = "displayAllComments.phtml";
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
        
        //inclure le template qui va générer la partie html qui doit afficher l'article
        include 'views/searchAjax.phtml';
    }
    

}