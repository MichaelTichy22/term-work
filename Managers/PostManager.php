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
    public function createPost(DatabaseManager $db, $parameters, $tags = [])
    {
        $query = 'INSERT INTO '.$this->table.' (title, content, create_date, id_user) VALUES (?, ?, \''.date('Y-m-d H:i:s').'\', ?)';
        $result = $db->query($query,$parameters);

        $postTagManager = new PostTagManager();

        if (!empty($tags)) {
            $postId = $db->getLastId();
            foreach ($tags as $tag) {
                $postTagManager->createPostTag($db, [$postId, $tag]);
            }
        }

        return $result;
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editPost(DatabaseManager $db, $parameters, $tags = []){
        $query = 'UPDATE '.$this->table.' SET title = ?, content = ?  WHERE id_post = ?';
        $result = $db->query($query,$parameters);

        $postTagManager = new PostTagManager();

        $postTagManager->deleteByPostId($db, [$parameters[2]]);

        if (!empty($tags)) {
            $postId = $parameters[2];
            foreach ($tags as $tag) {
                $postTagManager->createPostTag($db, [$postId, $tag]);
            }
        }

        return $result;
    }
}