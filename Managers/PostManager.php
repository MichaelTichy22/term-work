<?php

class PostManager extends EntityManager
{
    public function __construct()
    {
        $this->table = 'post';
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function createPost(DatabaseManager $db, $parameters)
    {
        $query = 'INSERT INTO '.$this->table.' (title, content, create_date, id_user) VALUES (?, ?, \''.date('Y-m-d H:i:s').'\', ?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editPost(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET title = ?, content = ?  WHERE id_post = ?';
        return $db->query($query,$parameters);
    }
}