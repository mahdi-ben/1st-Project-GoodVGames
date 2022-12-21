<?php

namespace Models;


class Users extends Database {
    
    public function getAllUsers() {
        
        $req = "SELECT * FROM users";
        return $this ->findAll($req);
    }
    
    public function getUser($id): array|bool {
        
        $sql = "SELECT * 
                FROM Users 
                WHERE id = :id";

        $userInsert = $this->bdd->prepare($sql);
        $userInsert->bindValue(':id', $id);
        $userInsert->execute([
            'id' => $id
        ]);
        
        $data  = $userInsert->fetch();
        if ($data === false) 
        {
            return false;
        }
        return $data;
    }
    
    public function addUser($username, $email, $password, $age) {

        $data = [$username, $email, $password, $age];
        $this-> addOne("users", "username, email, password, birthday", "?,?,?,?", $data);
    }
    
    public function getUserByEmail($email) {
        
        return $this ->getOneByEmail("users", $email);
    }
    
    public function getByUsername($username)  {
        
        return $this ->getOneByUsername("users", $username);
    }
    
    public function deleteUserTops($id): bool  {
    
        $sql    = 'DELETE FROM users WHERE user_id = :id' ;

        $userDelete = $this->bdd->prepare($sql);
        $userDelete->bindValue(':id', $id);
        $result = $userDelete->execute([
            'id' => $id
        ]);
        
        return $result;
    }
}