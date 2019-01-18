<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 30.12.18
 * Time: 13:56
 */

class PositionForm extends Form
{
    function build(DatabaseManager $db = null)
    {
        $this->addElement('name', 'Název', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'shorterThan255',
                'notBlank',
            ],
        ]);
    }
}