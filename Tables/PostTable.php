<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.12.18
 * Time: 14:07
 */

class PostTable extends Table
{
    function build(DatabaseManager $db = null)
    {
        $this->addColumn('id_post', 'ID');
        $this->addColumn('title', 'Titulek');
        $this->addColumn('content', 'Obsah');
        $this->addColumn('create_date', 'Vytvořeno');
        $this->addColumn('id_user', 'User_id');
    }
}