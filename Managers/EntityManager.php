<?php

abstract class EntityManager
{
    protected $table = '';

    /**
     * @param DatabaseManager $db
     * @return mixed
     */
    public function getAll(DatabaseManager $db, $orderBy, $order)
    {
        $query = 'SELECT * FROM '.$this->table.' ORDER BY '.$orderBy.' '.$order;
        return $db->queryAll($query);
    }

    /**
     * @param DatabaseManager $db
     * @param $id
     * @return mixed
     */
    public  function  getById(DatabaseManager $db, $id)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE id = ?';
        return $db->queryOne($query, [$id]);
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

    /**
     * @param $table
     * @return mixed
     */
    public function getLastId(DatabaseManager $db)
    {
        $query = 'SELECT MAX(id) AS max_id FROM ?';
        return $db->queryOne($query, [$this->table]);
    }
}