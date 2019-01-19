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
        $query = 'UPDATE '.$this->table.' SET name = ?, description = ?, id_order = ?, id_user = ?  WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $id
     * @return mixed
     */
    public function deleteById(DatabaseManager $db, $id)
    {
        $query = 'DELETE FROM '.$this->table.' WHERE id_'.$this->table.' = ?';
        return $db->query($query, [$id]);
    }

    public function getAll(DatabaseManager $db, $orderBy, $order)
    {
        $query = 'SELECT t.id_task, t.name, t.description, t.hours_done, t.state, t.id_user, t.id_order, u.name AS firstname, u.surname, o.name AS order_name
                  FROM task t LEFT JOIN user u ON t.id_user = u.id_user 
                  LEFT JOIN `order` o ON t.id_order = o.id_order ORDER BY '.$orderBy.' '.$order;
        return $db->queryAll($query);
    }
}