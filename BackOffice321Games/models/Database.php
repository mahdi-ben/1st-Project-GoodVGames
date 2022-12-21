<?php

namespace Models;



class Database {
    
    protected $bdd;
    
    public function __construct() {
        $this->bdd = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS); 
    }
    
    protected function addOne(string $table, string $columns, string $values, $data ) {
        $query = $this->bdd->prepare('INSERT INTO ' . $table . '(' . $columns . ') values (' . $values . ')');
        $query->execute($data);
        $query->closeCursor();
    }
    
    protected function findAll($req, $params = []) {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetchAll();
    }
    
    protected function getOneById($table, $id) {
        $query = $this->bdd->prepare("SELECT * FROM" . $table . " WHERE id = ?");
        $query->execute([$id]);
        $data = $query->fecth();
        $query->closeCursor();
        return $data;
    }
    
    protected function getOneByEmail($table, $email) {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE email = ?");
        $query->execute([$email]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    
    protected function getOneByUsername($table, $username) {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE username = ?");
        $query->execute([$username]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    protected function getOneByName($table, $name) {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE name = ?");
        $query->execute([$name]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    
    protected function getTopsByUser($table, $games) {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE user_id = ?");
        $query->execute([$games]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    
    protected function getGamesByUserTops($table, $games) {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE videogames_id = ?");
        $query->execute([$games]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    
    protected function getGameById($table, $column, $id, $orderBy="") {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE ". $column ." = ? " . $orderBy);
        $query->execute([$id]);
        $data = $query->fetchAll();
        $query->closeCursor();
        return $data;
    }
    
    protected function updateOne($table, $newData, $condition, $val)  {
        //var_dump($val); die;
        $sets = '';
        // Préparation du data binding grâce à une boucle
        foreach( $newData as $key => $value )  {
            // On concatène le nom des colonnes et le paramètre du data binding:  clé = :clé,
            $sets .= $key . ' = :' . $key . ',';
        }
        // On supprime le dernier caractère, donc la derniere virgule
        $sets = substr($sets, 0, -1);
        // requete SQL
        $sql = "UPDATE " . $table . " SET " . $sets . " WHERE " . $condition . " = :" . $condition;
        // On prépare la requete SQL
        $query = $this->bdd->prepare( $sql );
        // Pour chaque valeur, on lie la valeur de la clé à chaque :clé
        foreach( $newData as $key => $value )  {
            $query->bindValue(':' . $key, $value);
        }
        // On lie la valeur (par ex, l'id) de la condition à  :condition
        $query->bindValue( ':' . $condition, $val);
        $query->execute();
        $query->closeCursor();
    }

}