<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.12.18
 * Time: 12:03
 */

abstract class Table
{
    private $dataSet;
    private $columns;

    public function __construct($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    abstract function build(DatabaseManager $db = null);

    public function addColumn($databaseColumnName, $th)
    {
        $this->columns[$databaseColumnName] = ['header'=>$th];
    }

    public function renderTable()
    {
        require "./Views/Table/table.phtml";
    }

    /**
     * @return mixed
     */
    public function getDataSet()
    {
        return $this->dataSet;
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }
}