<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 29.12.18
 * Time: 15:40
 */

class TaskManager extends EntityManager
{
    public function __construct()
    {
        $this->table = 'task';
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function createTask(DatabaseManager $db, $parameters)
    {
        $query = 'INSERT INTO '.$this->table.' (name, description, id_order, id_user) VALUES (?, ?, ?, ?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editTask(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET name = ?, description = ?, id_user = ?, id_order = ?  WHERE id = ?';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $id
     * @return mixed
     */
    public function deleteById(DatabaseManager $db, $id)
    {
        $query = 'DELETE FROM '.$this->table.' WHERE id = ?';
        return $db->query($query, [$id]);
    }
}