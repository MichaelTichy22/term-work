<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 15:39
 */

class OrderForm extends Form
{

    function build(DatabaseManager $db = null)
    {
        $this->addElement('name', 'Název', 'input', [
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
    }
}