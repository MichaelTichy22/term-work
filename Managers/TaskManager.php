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
        $userManager = new UserManager();
        $orderManager = new OrderManager();
        if ($orderManager->getById($db, $parameters[2]) == null){
            $parameters[2] = null;
        }
        if ($userManager->getById($db, $parameters[3]) == null){
            $parameters[3] = null;
        }
        $query = 'INSERT INTO '.$this->table.' (name, description, id_order, id_user) VALUES (?, ?, ?, ?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editTask(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET name = ?, description = ?, id_order = ?, id_user = ?, state = ?  WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editTaskState(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET state = ?  WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editTaskHoursState(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET hours_done = ?, state = ?  WHERE id_'.$this->table.' = ?';
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

    public function getAllByUser(DatabaseManager $db, $orderBy, $order, $idUser)
    {
        $query = 'SELECT t.id_task, t.name, t.description, t.hours_done, t.state, t.id_user, t.id_order, u.name AS firstname, u.surname, o.name AS order_name
                  FROM task t LEFT JOIN user u ON t.id_user = u.id_user 
                  LEFT JOIN `order` o ON t.id_order = o.id_order WHERE t.id_user = ? ORDER BY '.$orderBy.' '.$order;
        return $db->queryAll($query, [$idUser]);
    }
}