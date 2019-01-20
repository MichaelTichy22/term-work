<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 20.1.19
 * Time: 17:48
 */

class UserPasswordForm extends Form
{
    public function build(DatabaseManager $db = null)
    {
        $this->addElement('password_old', 'Staré heslo', 'input', [
            'type' => 'password',
            'required' => '',
            'constraints' => [
                'notBlank',
                'shorterThan32',
            ],
        ]);
        $this->addElement('password_new', 'Nové heslo', 'input', [
            'type' => 'password',
            'required' => '',
            'constraints' => [
                'notBlank',
                'shorterThan32',
            ],
        ]);
        $this->addElement('change_password', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Změnit');
    }
}