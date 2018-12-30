<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 29.12.18
 * Time: 15:38
 */

class PositionManager extends EntityManager
{
    /**
     * PositionManager constructor.
     */
    public function __construct()
    {
        $this->table = 'position';
    }


    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function createPosition(DatabaseManager $db, $parameters)
    {
        $query = 'INSERT INTO '.$this->table.' (name) VALUES (?)';
        return $db->query($query,$parameters);
    }

    /**
     * @param DatabaseManager $db
     * @param $parameters
     * @return mixed
     */
    public function editPosition(DatabaseManager $db, $parameters){
        $query = 'UPDATE '.$this->table.' SET name = ? WHERE id_'.$this->table.' = ?';
        return $db->query($query,$parameters);
    }
}