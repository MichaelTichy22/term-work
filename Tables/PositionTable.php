<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 30.12.18
 * Time: 13:52
 */

class PositionTable extends Table
{
    function build(DatabaseManager $db = null)
    {
        $this->addColumn('id_position', 'ID');
        $this->addColumn('name', 'Název');
    }

}