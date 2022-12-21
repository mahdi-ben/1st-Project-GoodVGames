<?php

namespace Controllers;

class UsersAdminController {
    
    public function formConnect() {
        
        $template = "login.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function submitFormConnect() {
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
                // Verifions si l'email de l'utilisateur existe dans notre bdd et si il a le role admin
                $model = new \Models\Users();
                $resultExist = $model->getUserByEmail($_POST['email']);
                
                if(empty($resultExist))
                    $errors[] = "Erreur d'identification";
                //  var_dump($resultExist);
                
                if(count($errors) == 0) {
                    
                    if(password_verify($_POST['password'], $resultExist["password"]) == false) 
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
                            $errors[] = "Vous êtes un utilisateur simple et non un administrateur, accès refusé";
                            $errors[] = "Vous allez être redirigé sur le site GoodVGames";
                            $template = "login.phtml";
                            include_once 'views/layout.phtml';
                    }
                    if(empty($resultExist['role']) || $resultExist['role'] !== "admin")
                        $errors[] = "Erreur d'identification";
                        
                    if(count($errors) == 0) {
                    
                        $_SESSION['connected'] = true;
                        $_SESSION['user'] = false;
                        $_SESSION['admin'] = [
                            'id'        => $resultExist['id'],
                            'username'  => $resultExist['username'],
                            'email'     => $resultExist['email'],
                            'birthday'  => $resultExist['birthday'],
                            'role'      => $resultExist['role']
                        ];
                        $valids[] = "Vous êtes connnecté sur le site GoodVGames en tant qu'Administrateur";
                        $template = "login.phtml";
                        include_once 'views/layout.phtml';
                    }
                }    
            }    
        }
        $template = "login.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function disconnectAdmin() {
        
        $_SESSION['connected'] = false;
        $_SESSION['admin'] = [];
        session_destroy();
        header("location: index.php?road=home");
        exit;
    }
    
    public function displayUsers() {
        
        $model = new \Models\Users();
        $users = $model->getAllUsers();
        $numberOfUsers = count($users);
        $template = "usersCrud.phtml";
        include_once 'views/layout.phtml';
    } 
    
    public function displayUsersArray() {
        
        $model = new \Models\Users();
        $users = $model->getAllUsers();
        $numberOfUsers = count($users);
        $template = "usersCrudArray.phtml";
        include_once 'views/layout.phtml';
    } 

    public function formUsers() {
        
        $model = new \Models\Users();
        $roles = $model->getRole();
        $template = "userRegister.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function addUser(): void {
        
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
                $errors[] = "Votre année de naissance n'est pas bonne";
            
            //est-ce que le role de l'utilisateur est bien défini
            if($_POST['role'] == "vide") 
                $errors[] = "Vous n'avez pas défini le role de l'utilisateur";
               
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
                    $role = $_POST['role'];
                    
                    $model = new \Models\Users();
                    $model->addUser($username, $email, $password, $age, $role);
                    $users = $model->getAllUsers();
                    $numberOfUsers = count($users);
                    $valids[] = 'Felicitation, le nouvel utilisateur a bien été enregistré';
                    $template = "usersCrud.phtml";
                    include_once 'views/layout.phtml';
                }
            }    
            $model = new \Models\Users();
            $roles = $model->getRole();
            $template = "userRegister.phtml";
            include_once 'views/layout.phtml';
        }
        $template = "userRegister.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function update()   {
        
        $errors = [];
        $valids = [];
        
        if (isset($_POST) && empty($_POST)) {
            $id = $this->getIdUser();
            //  var_dump($id);
            if ($id === false)   {
                $errors[] = 'l\'utilisateur n\'existe pas dans nos serveurs';
                $model = new \Models\Users();
                $users = $model->getAllUsers();
                $numberOfUsers = count($users);
                $template = "usersCrud.phtml";
                include_once 'views/layout.phtml';
            }   else {
                    $model = new \Models\Users();
                    $data = $model->getUser($id);
    
                    if ($data === false)   {
                        $errors[] = "l'utilisateur n'existe pas dans la bdd";
                        $model = new \Models\Users();
                        $users = $model->getAllUsers();
                        $numberOfUsers = count($users);
                        $template = "usersCrud.phtml";
                        include_once 'views/layout.phtml';
                    }   else {
                        //    var_dump($data);
                            $model = new \Models\Users();
                            $roles = $model->getRole();
                            $template = 'updateUsers.phtml';
                            require 'views/layout.phtml';
                        }
                }
        } else {
            if(array_key_exists('username', $_POST) && array_key_exists('email', $_POST)
                && array_key_exists('password', $_POST) && array_key_exists('birthday', $_POST) && array_key_exists('role', $_POST)) {

                // username doit contenir au moins 3 caractères
                if(strlen($_POST['username']) < 3) 
                    $errors[] = "Veuillez renseigner au moins 3 caractères pour le nom";
                    
                // email est bien un format email et valide
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                    $errors[] =  'Veuillez renseigner un email valide SVP !';
 
                //Le password doit avoir 6 caractères, bloquer sinon
                if(strlen($_POST['password']) < 6)
                    $errors[] = "Veuillez renseigner au moins 6 caractères pour le mot de pass";
                
                //est-ce que le role de l'utilisateur est bien défini
                if($_POST['role'] == "vide") 
                    $errors[] = "Vous n'avez pas défini le role de l'utilisateur";
                //est-ce que l'age de l'utilisateur est cohérent 
                if($_POST['birthday'] < 1920 || $_POST['birthday'] > 2015) 
                    $errors[] = "Votre année de naissance n'est pas bonne";
        
                if(count($errors) == 0) {
                    $id = strip_tags($_POST['id']);
                    $username = strip_tags($_POST['username']);
                    $email = strip_tags($_POST['email']);
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $birthday = strip_tags($_POST['birthday']);
                    $role = strip_tags($_POST['role']);
                    $model = new \Models\Users();
                    $result = $model->updateUser($id, $username, $email, $password, $birthday, $role);
                    $users = $model->getAllUsers();
                    $numberOfUsers = count($users);
            //        var_dump($result);
                    $valids[] = 'les modifications ont été faites avec succès';
                    $template = "usersCrud.phtml";
                    include_once 'views/layout.phtml';
                    exit;
                }
            }   
                $id = strip_tags($_POST['id']);
                $model = new \Models\Users();
                $data = $model->getUser($id);
                $roles = $model->getRole();
                $errors[] = 'Echec de la modification';
                $template = 'updateUsers.phtml';
                require 'views/layout.phtml';
        }
    }

    public function deleteUser(): void  {
        
        $users = [];
        $id = $this->getIdUser();
        //  var_dump($id);
        
        if ($id === false)  {
            
            $model = new \Models\Users();
            $users = $model->getAllUsers();
            $errors[] = 'l\'utilisateur n\'existe pas dans la bdd';
            $template = "usersCrud.phtml";
            include_once 'views/layout.phtml';
        } 
        else  {
            $model = new \Models\Users();
            $result = $model->deleteUserById($id);
            //  var_dump($result);
            $users = $model->getAllUsers();
            $numberOfUsers = count($users);
            $valids[] = 'Felicitation, l\'utilisateur a bien été supprimé';
            $template = "usersCrud.phtml";
            include_once 'views/layout.phtml';
        }
    }
    
    public function getIdUser() : int|bool   {
        
        if (isset($_GET['road']) && isset($_GET['id']) && !empty($_GET['id'])) {
            $id = strip_tags($_GET['id']);
            return $id;
        //    var_dump($id);
        } 
        return false;
    }
}