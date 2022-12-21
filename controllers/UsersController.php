<?php

namespace Controllers;


class UsersController {
    
    public function formUserRegister() {
        
        $template = "userRegister.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function formConnect() {
    
        $template = "login.phtml";
        include_once 'views/layout.phtml';
    }

    public function add(): void {
    
        $errors = [];
        $valids = [];
    
        if(array_key_exists('username', $_POST) && array_key_exists('email', $_POST)
            && array_key_exists('password', $_POST) && array_key_exists('age', $_POST)) {

            // username doit contenir au moins 3 caractères
            if(strlen($_POST['username']) < 3) 
                $errors[] = "Veuillez renseigner au moins 3 caractères pour le nom";
    
            // email est bien un format email et valide
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                $errors[] =  'Veuillez renseigner un email valide SVP !';

            //Le password doit avoir 6 caractères, bloquer sinon
            if(strlen($_POST['password']) < 6)
                $errors[] = "Veuillez renseigner au moins 6 caractères pour le mot de pass";
    
            //est-ce que le mot de pass est bien confirmé et identique
            if($_POST['password'] != $_POST['password_verify'])
                $errors[] = "Vous n'avez pas confirmé correctement votre mot de pass";
            
            //est-ce que l'age de l'utilisateur est cohérent    
            if($_POST['age'] < 1920 || $_POST['age'] > 2015) 
                $errors[] = "Votre année de naissance n'est pas authorisé";
        
            if(count($errors) == 0) {
                // Verifions si l'utilisateur n'existe pas déjà dans notre bdd(via son email)
                $model = new \Models\Users();
                $resultExist = $model->getUserByEmail($_POST['email']);
                $resultUsername = $model->getByUsername($_POST['username']);
                if(!empty($resultExist))
                    $errors[] = "Cette adresse email existe déjà";
                if(!empty($resultUsername))
                    $errors[] = "Ce nom d'utilisateur existe déjà, veuillez en choisir un autre svp";
                
                if(count($errors) == 0) {
                    // On peut ajouter l'utilisateur dans la base de donnée
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $age = $_POST['age'];
                
                    $model = new \Models\Users();
                    $model->addUser($username, $email, $password, $age);
    
                    $valids[] = 'Felicitation, votre compte utilisateur a bien été enregistré';
                    $valids[] = 'Vous pouvez maintenant vous connecter à votre compte dans Connexion';
                    $template = "userRegister.phtml";
                    include_once 'views/layout.phtml';
                    header("refresh:5;url= ?road=home");
                    exit;
                }
            }    
        }
        $template = "userRegister.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function submitformConnect() {
        // Vérifier si le formulaire est bien rempli et correctement(filter_var et strlen)
        // Verifier si il y a une concordance entre l'email du formulaire et la bdd
        // Si pas de concordance->afficher message d'erreurs
        // Si concordance->vérifier que le mot de pass est concordant(form et bdd)
        // Si pas identique-> message d'erreurs
        // Si identique-> établir la connection en utilisant le $_SESSION et redirection à la page d'accueil
        $errors = [];
        $valids = [];

        if(array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {    
        
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $errors[] =  'Veuillez renseigner un email valide SVP !';

            if(strlen($_POST['password']) < 6)
            $errors[] = "Veuillez renseigner au moins 6 caractères pour le mot de pass";

            if(count($errors) == 0) {
                // Verifions si l'email de l'utilisateur existe dans notre bdd
                $model = new \Models\Users();
                $resultExist = $model->getUserByEmail($_POST['email']);

                if(empty($resultExist))
                    $errors[] = "Erreur d'identification";

                if(count($errors) == 0) {
                    
                    if(password_verify($_POST['password'], $resultExist["password"]) == false) 
                        $errors[] = "Erreur d'identification"; 
                      //  var_dump(password_verify($_POST['password'], $resultExist["password"]));
                        
                    if(empty($resultExist['role']) || $resultExist['role'] !== "admin" && $resultExist['role'] !== "user")
                    $errors[] = "Erreur d'identification";
                }    
                    
                if(count($errors) == 0) {
                        
                    if($resultExist['role'] == "user") {
                        
                        $_SESSION['connected'] = true;
                        $_SESSION['admin'] = false;
                        $_SESSION['user'] = [
                            'id'        => $resultExist['id'],
                            'username'  => $resultExist['username'],
                            'email'     => $resultExist['email'],
                            'birthday'  => $resultExist['birthday'],
                            'role'      => $resultExist['role']
                        ];
                        $valids[] = 'Vous êtes connnecté sur le site GoodVGames';
                        $valids[] = 'Vous avez maintenant accès à la totalité du site';
                        $template = "login.phtml";
                        include_once 'views/layout.phtml';
                        header("refresh:3;url= ?road=home");
                        exit;
                    }

                    if($resultExist['role'] == "admin") {
                        
                        $_SESSION['connected'] = true;
                        $_SESSION['user'] = false;
                        $_SESSION['admin'] = [
                            'id'        => $resultExist['id'],
                            'username'  => $resultExist['username'],
                            'email'     => $resultExist['email'],
                            'birthday'  => $resultExist['birthday'],
                            'role'      => $resultExist['role']
                        ];
                        $valids[] = 'Vous êtes connnecté sur le site GoodVGames';
                        $valids[] = 'Vous avez maintenant accès à la totalité du site';
                        $template = "login.phtml";
                        include_once 'views/layout.phtml';
                        header("refresh:3;url= ?road=home");
                        exit;
                    }
                }  
            }            
        }
        $template = "login.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function disconnectUser() {
        
        $_SESSION['connected'] = false;
        $_SESSION['admin'] = [];
        $_SESSION['user'] = [];
        session_destroy();
        header("location: index.php?road=home");
        exit;
    }
    
    public function displayUsers() {
        
        $model = new \Models\Users();
        $users = $model->getAllUsers();
        $numberOfUsers = count($users);
        require_once('config/config.php');
        $template = "displayUsers.phtml";
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
    
    
}