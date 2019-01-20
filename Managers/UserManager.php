<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 7.10.18
 * Time: 11:37
 */

class UserManager extends EntityManager
{
    public function __construct()
    {
        $this->table = 'user';
    }

    /**
     * @param DatabaseManager $db
     * @param $name
     * @return mixed
     */
    public function getByName(DatabaseManager $db, $name)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE username = ?';
        return $db->queryOne($query,[$name]);
    }

    /**
     * @param DatabaseManager $db
     * @param $name
     * @return mixed
     */
    public function getByEmail(DatabaseManager $db, $name)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE email = ?';
        return $db->queryOne($query,[$name]);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function createUser(DatabaseManager $db, $parameters)
    {
        $query = 'INSERT INTO '.$this->table.' (username, password, email, name, surname) VALUES (?,?,?,?,?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $id
     * @return mixed
     */
    public  function  getById(DatabaseManager $db, $id)
    {
        $query = 'SELECT u.id_user, u.username, u.email, u.name, u.surname, u.role, u.wage, u.hours_worked, u.id_position, u.password, p.name AS position_name, w.id_workplace, w.name AS workplace_name  
                    FROM user u LEFT JOIN position p ON u.id_position = p.id_position
                    LEFT JOIN workplace w ON u.id_workplace = w.id_workplace WHERE u.id_user = ?';
        return $db->queryOne($query, [$id]);
    }

    public function getAll(DatabaseManager $db, $orderBy, $order)
    {
        $query = 'SELECT u.id_user, u.username, u.email, u.name, u.surname, u.role, u.wage, u.hours_worked, u.id_position, p.name AS position_name, w.id_workplace, w.name AS workplace_name  
                    FROM user u LEFT JOIN position p ON u.id_position = p.id_position
                    LEFT JOIN workplace w ON u.id_workplace = w.id_workplace ORDER BY '.$orderBy.' '.$order;
        return $db->queryAll($query);
    }

    /**
     * @param DatabaseManager $db
     * @param $id
     * @return mixed
     */
    public function getAllActive(DatabaseManager $db, $orderBy, $order)
    {
        $query = 'SELECT u.id_user, u.username, u.email, u.name, u.surname, u.role, u.wage, u.hours_worked, u.id_position, p.name AS position_name, w.id_workplace, w.name AS workplace_name  
                    FROM user u LEFT JOIN position p ON u.id_position = p.id_position
                    LEFT JOIN workplace w ON u.id_workplace = w.id_workplace WHERE u.role != 0 ORDER BY '.$orderBy.' '.$order;
        return $db->queryAll($query);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editUser(DatabaseManager $db, $parameters){
        if ($parameters[4] == null && $parameters[5] != null) {
            $query = 'UPDATE '.$this->table.' SET email = ?, name = ?, surname = ?, wage = ?, id_position = NULL, id_workplace = ?, role = ? WHERE id_'.$this->table.' = ?';
            unset($parameters[4]);
        } else if($parameters[5] == null && $parameters[4] != null) {
            $query = 'UPDATE '.$this->table.' SET email = ?, name = ?, surname = ?, wage = ?, id_position = ?, id_workplace = NULL, role = ? WHERE id_'.$this->table.' = ?';
            unset($parameters[5]);
        } else if ($parameters[4] == null && $parameters[5] == null) {
            $query = 'UPDATE '.$this->table.' SET email = ?, name = ?, surname = ?, wage = ?, id_position = NULL, id_workplace = NULL, role = ? WHERE id_'.$this->table.' = ?';
            unset($parameters[4]);
            unset($parameters[5]);
        }else {
            $query = 'UPDATE '.$this->table.' SET email = ?, name = ?, surname = ?, wage = ?, id_position = ?, id_workplace = ?, role = ? WHERE id_'.$this->table.' = ?';

        }
        $newParameters = [];
        foreach ($parameters as $parameter) {
            $newParameters[] = $parameter;
        }

        return $db->query($query,$newParameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function changePassword(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET password = ? WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function addHours(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET hours_worked = ? WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }
}