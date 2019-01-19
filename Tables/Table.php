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
    private $name;

    public function __construct($dataSet, $name)
    {
        $this->dataSet = $dataSet;
        $this->name = $name;
    }

    abstract function build(DatabaseManager $db = null);

    public function addColumn($databaseColumnName, $th, $join = '')
    {
        $this->columns[$databaseColumnName] = ['header'=>$th];
    }

    public function renderTable()
    {
        if (empty($this->getDataSet())){
            echo "Tabulka je prázdná";
        }else{
            require "./Views/Table/table.phtml";
        }
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

}