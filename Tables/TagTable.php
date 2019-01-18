<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 13.1.19
 * Time: 19:12
 */

class TagTable extends Table
{

    function build(DatabaseManager $db = null)
    {
        $this->addColumn('id_tag','ID');
        $this->addColumn('name', 'Název');
    }
}