<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 29.12.18
 * Time: 15:36
 */

class OrderManager extends EntityManager
{
    /**
     * OrderManager constructor.
     */
    public function __construct()
    {
        $this->table = 'order';
    }


    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function createOrder(DatabaseManager $db, $parameters)
    {
        $query = 'INSERT INTO `'.$this->table.'` (name, description) VALUES (?, ?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editOrder(DatabaseManager $db, $parameters){
        $query = 'UPDATE `'.$this->table.'` SET name = ?, description = ? WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }
}