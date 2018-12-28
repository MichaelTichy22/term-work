<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6.10.18
 * Time: 17:07
 */

class DatabaseManager
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $connection;

    /**
     * Database constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $database
     */
    public function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect()
    {
        try{
            if (!isset($this->connection)){
                $options = [
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
                $this->connection = @new PDO(
                    'mysql:host='.$this->host.';dbname='.$this->database,
                    $this->user,
                    $this->password,
                    $options
                );
            }
        }catch (PDOException $e){
            die($e);
        }
    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function queryAll($query, $parameters = [])
    {
        $result = $this->connection->prepare($query);
        $result->execute($parameters);
        return $result->fetchAll();
    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function queryOne($query, $parameters = [])
    {
        $result = $this->queryAll($query, $parameters);
        if (array_key_exists (0, $result)){
            return $result[0];
        }else{
            return null;
        }

    }

    /**
     * @param $query
     * @param array $parameters
     * @return mixed
     */
    public function query($query, $parameters = [])
    {
        $result = $this->connection->prepare($query);
        $result->execute($parameters);
        return $result->rowCount();
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    public function getLastId()
    {
        return $this->connection->lastInsertId();
    }
}