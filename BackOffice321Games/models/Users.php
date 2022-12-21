<?php

namespace Models;


class Users extends Database {
    
    public function getAllUsers() {
        
        $req = "SELECT * FROM users";
        return $this ->findAll($req);
    }
    
    public function getUser($id): array|bool {
        
        $sql = "SELECT * 
                FROM users 
                WHERE id = :id";

        $userInsert = $this->bdd->prepare($sql);
        $userInsert->bindValue(':id', $id);
        $userInsert->execute();
        $data = $userInsert->fetch();
        
        if ($data === false) 
        {
            return false;
        }
        return $data;
    }
    
    public function getUserByEmail($email) {
        
        return $this ->getOneByEmail("users", $email);
    }

    public function getByUsername($username)  {
        
        return $this ->getOneByUsername("users", $username);
    }
    
    public function getRole()  {
        
        $req = "SELECT role FROM users GROUP BY role ASC";
        return $this->findAll($req);
    }
    
    public function checkUserRegister(): bool   {
        
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['birthday']) || empty($_POST['role'])) 
            return false;
        else 
            return true;
    }
    
    public function addUser($username, $email, $password, $age, $role) {

        $data = [$username, $email, $password, $age, $role];
        $this-> addOne("users", "username, email, password, birthday, role", "?,?,?,?,?", $data);
    }
    
    public function updateUser($id, $username, $email, $password, $birthday, $role): bool  {
    
        $data = [   'id'       => $id,
                    'username' => $username,
                    'email'    => $email,
                    'password' => $password,
                    'birthday' => $birthday,
                    'role'     => $role
                ];
        $val = $data['id'];
        $this-> updateOne("users", $data, 'id', $val);
        return true;
    }
    
    public function deleteUserById($id)  {
    
        $sql    = 'DELETE FROM users WHERE id = :id' ;
    
        $userDelete = $this->bdd->prepare($sql);
        $userDelete->bindValue('id', $id);
        $result = $userDelete->execute([
            'id' => $id
        ]);
    }
}