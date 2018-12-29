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
}