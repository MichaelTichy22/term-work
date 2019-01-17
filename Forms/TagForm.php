<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 13.1.19
 * Time: 19:33
 */

class TagForm extends Form
{
    function build(DatabaseManager $db = null)
    {
        $this->addElement('name','Název', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'shorterThan255',
                'notBlank',
            ],
        ]);
    }
}