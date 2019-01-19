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
        $this->addColumn('position_name', 'Pozice');
        $this->addColumn('workplace_name', 'Oddělení');
        if ($_SESSION['user']['role'] == 2) {
            $this->addColumn('wage', 'Plat na hodinu');
            $this->addColumn('hours_worked', 'Odpracované hodiny');
        }
    }
}