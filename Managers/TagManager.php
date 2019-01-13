<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 29.12.18
 * Time: 15:40
 */

class TagManager extends EntityManager
{
    /**
     * WorkplaceManager constructor.
     */
    public function __construct()
    {
        $this->table = 'tag';
    }

    /**
     * @param DatabaseManager $db
     * @param array $parameters
     * @return mixed
     */
    public function createTag(DatabaseManager $db, $parameters)
    {
        $query = 'INSERT INTO '.$this->table.' (name) VALUES (?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editTag(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET name = ? WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }
}