<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 16:25
 */

class TaskTable extends Table
{

    function build(DatabaseManager $db = null)
    {
        $this->addColumn('id_task', 'ID');
        $this->addColumn('name', 'Název');
        $this->addColumn('description', 'Popis');
        $this->addColumn('hours_done', 'Odpracované hodiny');
        $this->addColumn('state', 'Stav');
        $this->addColumn('description', 'Popis');
        $this->addColumn('id_user', 'Zaměstnanec');
        $this->addColumn('id_order', 'Zákázka');
    }
}