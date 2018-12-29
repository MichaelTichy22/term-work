<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.12.18
 * Time: 16:04
 */

class UserTable extends Table
{

    function build(DatabaseManager $db = null)
    {
        $this->addColumn('id_user', 'ID');
        $this->addColumn('name', 'Jméno');
        $this->addColumn('surname', 'Příjmeni');
        $this->addColumn('email', 'Email');
    }
}