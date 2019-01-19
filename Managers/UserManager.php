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
        $query = 'SELECT u.id_user, u.username, u.email, u.name, u.surname, u.role, u.wage, u.hours_worked, u.id_position, p.name AS position_name, w.id_workplace, w.name AS workplace_name  
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
     * @param $parameters
     * @return mixed
     */
    public function editUser(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET email = ?, name = ?, surname = ?, wage = ?, id_position = ?, id_workplace = ? WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }
}