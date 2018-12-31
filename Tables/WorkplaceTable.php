<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 13:37
 */

class WorkplaceTable extends Table
{

    function build(DatabaseManager $db = null)
    {
        $this->addColumn('id_workplace','ID');
        $this->addColumn('name', 'Název');
    }
}