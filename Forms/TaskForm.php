<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 16:40
 */

class TaskForm extends Form
{
    function build(DatabaseManager $db = null)
    {
        $orderManager = new OrderManager();
        $userManager = new UserManager();
        $users = $userManager->getAllActive($db, 'id_user', 'ASC');
        $orders = $orderManager->getAll($db, 'id_order', 'ASC');

        $this->addElement('name', 'Název', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'shorterThan255',
                'notBlank',
            ],
        ]);
        $this->addElement('description', 'Popis', 'textArea', [
            'rows' => '4',
            'cols' => '50',
            'required' => '',
            'constraints' => [
                'shorterThan65535',
                'notBlank',
            ],
        ]);
        $this->addElement('orders', 'Zakázka', 'select',[
            'options'=>$this->getSelectOptions($orders, 'id_order', ['name']),
            'required' => 'required',
            'constraints' => [
                'notNull',
            ],
        ]);
        $this->addElement('users', 'Zaměstanec', 'select',[
            'options'=>$this->getSelectOptions($users, 'id_user', ['name', 'surname']),
            'required' => 'required',
            'constraints' => [
                'notNull',
            ],
        ]);
    }
}