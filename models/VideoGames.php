<?php 

namespace Models;


class VideoGames extends Database {
    
    public function getGameOfTopsUser($id, $userId): array|bool  {
        
        $sql = "SELECT tops.id AS tops_id, tops.user_id, tops.videogames_id, 
                        tops.comment, tops.rating, tops.console_id, 
                        users.username, 
                        videogames.id, videogames.name, videogames.image, 
                        console.id 
                FROM tops 
                INNER JOIN videogames ON tops.videogames_id = videogames.id 
                INNER JOIN console ON tops.console_id = console.id 
                INNER JOIN users ON tops.user_id = users.id 
                WHERE videogames_id = :id_jeux AND user_id = :id ";
        
        $query = $this->bdd->prepare($sql);
        $query->bindValue(':id_jeux', $id);
        $query->bindValue(':id', $userId);
        $query->execute();
        $data = $query->fetch();
        
        if ($data === false) 
        {
            return false;
        }
        return $data;
    }
    
    public function getAllTops() {
        
        $req = "SELECT  videogames.name AS jeux, videogames.descriptif, videogames.multiplayerslocal,
                        videogames.online, videogames.price, videogames.id AS id_game,
                        videogames.image, 
                        category.category_game AS typeofgame,
                        tops.videogames_id, AVG(rating) AS avgRating,
                        pegi.pegi_age AS pegi 
                FROM tops 
                INNER JOIN videogames ON tops.videogames_id = videogames.id 
                INNER JOIN category ON videogames.category_id = category.id 
                INNER JOIN users ON tops.user_id = users.id 
                INNER JOIN console ON tops.console_id = console.id 
                INNER JOIN pegi ON videogames.pegi_id = pegi.id 
                GROUP BY tops.videogames_id 
                ORDER BY avgRating DESC ";
        
        return $this ->findAll($req);
    }
    
    // Pour récuperer le top d'un utilisateur par l'id de l'utilisateur
    public function getOneTop($id) {
        
        $req =  "SELECT user_id, videogames_id, comment, rating, console_id,
            		users.id, users.username,
            		console.id, console_name,
                    videogames.id AS id_game, videogames.name AS jeux, videogames.image, videogames.descriptif
                FROM tops 
                INNER JOIN users
                	ON tops.user_id = users.id
                INNER JOIN console 
                    ON tops.console_id = console.id
                INNER JOIN videogames
                	ON tops.videogames_id = videogames.id
                WHERE tops.user_id LIKE ?
                GROUP BY videogames_id, user_id, comment, rating, console_id
                ORDER BY rating DESC, console_id ASC";
            
        return $this->findAll($req, [$id]);
    }
    
    public function getAllConsoles() {
        $req = "SELECT id, console_name FROM console ORDER BY id ASC";
        return $this->findAll($req);
    }
    
    public function getPlayList() {
        $req = "SELECT id, name FROM videogames ORDER BY name ASC";
        return $this->findAll($req);
    }
    
    public function getAllCategory() {
        $req = "SELECT id, category_game FROM category ORDER BY id ASC";
        return $this->findAll($req);
    }
    
    public function getAllPegi() {
        $req = "SELECT id, pegi_age FROM pegi ORDER BY id ASC";
        return $this->findAll($req);
    }
    
    public function getOneTopById($id) {
        
        return $this ->getById("tops", $id);
    }
    
    public function updateTopGame($userTop, $test, $content, $machine): bool  {
    
        $data = [   'id'                => $userTop,
                    'rating'            => $test,
                    'comment'           => $content,
                    'console_id'        => $machine
                ];
        $val = $data['id'];
        $this-> updateOne("tops", $data, 'id', $val);
        return true;
    }
    
    public function deleteGameById($id)  {
    
        $sql    = 'DELETE FROM tops WHERE id = :id' ;
    
        $gameDelete = $this->bdd->prepare($sql);
        $gameDelete->bindValue('id', $id);
        $result = $gameDelete->execute([
            'id' => $id
        ]);
    }
    
    public function addTopGames($data) {
            $this->addOne(  'tops',
                        '`user_id`, `videogames_id`, `comment`, `rating`, `console_id`',
                        '?,?,?,?,?',
                        $data);
    }
            
    public function getSearchTopGames($games) {
        
        $req = "SELECT videogames.name AS jeux, videogames.descriptif, videogames.multiplayerslocal,
                        videogames.online, videogames.price, videogames.id AS id_game,
                        videogames.image,
                        tops.videogames_id, AVG(tops.rating) AS avgRating,
                        category.category_game AS typeofgame,
                        pegi.pegi_age AS pegi
                FROM videogames 
                INNER JOIN pegi
                    ON videogames.pegi_id = pegi.id
                INNER JOIN tops
                    ON videogames.id = tops.videogames_id
                INNER JOIN category
                    ON videogames.category_id = category.id
                WHERE videogames.name LIKE ?
                GROUP BY id_game
                ORDER BY avgRating, yearrelease DESC";
        
        return $this->findAll($req, [$games]);
    }
    
    public function getOneGame($id) {
        
        $req = "SELECT videogames.name AS jeux, videogames.descriptif, videogames.multiplayerslocal,
                        videogames.online, videogames.price, videogames.id AS id_game,
                        videogames.yearrelease, videogames.image,
                        AVG(tops.rating) AS avgRating,
                        category.category_game AS typeofgame,
                        pegi.pegi_age AS pegi
                FROM videogames 
                INNER JOIN tops
                    ON videogames.id = tops.videogames_id
                INNER JOIN category
                    ON videogames.category_id = category.id
                INNER JOIN pegi
                    ON videogames.pegi_id = pegi.id
                WHERE videogames.id LIKE ?
                GROUP BY videogames.id";

        return $this ->findAll($req, [$id]);
    }
    
    public function getAllCommentsByGame($id)  {
        
        $req = "SELECT tops.id, tops.user_id, tops.videogames_id, tops.comment, tops.rating, tops.console_id,
                        users.username, videogames.id, console.id
                FROM tops
                INNER JOIN videogames
                    ON tops.videogames_id = videogames.id
                INNER JOIN console
                    ON tops.console_id = console.id
                INNER JOIN users
                    ON tops.user_id = users.id
                WHERE videogames_id LIKE ?";
                
        return $this ->findAll($req, [$id]);
    }
    
    public function getTopConsole()  {
        
        $req =  "SELECT videogames.name AS jeux, videogames.descriptif, videogames.multiplayerslocal,
                        videogames.online, videogames.price, videogames.id AS id_game,
                        videogames.image,
                        category.category_game AS typeofgame,
                        AVG(rating) AS avgRating,  
                        console.console_name AS console, console.id AS id_console,
                        pegi.pegi_age AS pegi
                FROM tops
                INNER JOIN videogames
                    ON tops.videogames_id = videogames.id
                INNER JOIN category
                    ON videogames.category_id = category.id
                INNER JOIN console
                    ON tops.console_id = console.id
                INNER JOIN pegi
                    ON videogames.pegi_id = pegi.id
                GROUP BY videogames.id, id_console
                ORDER BY id_console, avgRating DESC, jeux ASC ";
                
        return $this ->findAll($req);
    }
    
    
}