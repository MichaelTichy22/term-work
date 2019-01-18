<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 29.12.18
 * Time: 15:39
 */

class PostTagManager extends EntityManager
{


    /**
     * PostTagManager constructor.
     */
    public function __construct()
    {
        $this->table = 'post_tag';
    }

    public function createPostTag(DatabaseManager $db, $parameters) {
        $query = 'INSERT INTO '.$this->table.' (id_post, id_tag) VALUES (?, ?)';
        return $db->query($query,$parameters);
    }

    public function deleteByPostId(DatabaseManager $db, $parameters) {
        $query = 'DELETE FROM `'.$this->table.'` WHERE id_post = ?';
        return $db->query($query, $parameters);
    }
}