<?php

class LoginForm extends Form
{

    /**
     * @param DatabaseManager|null $db
     */
    public function build(DatabaseManager $db = null)
    {

        $this->addElement('username', 'Uživatelské jméno', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'noSpecialChars',
                'notBlank',
            ],
        ]);
        $this->addElement('password', 'Heslo', 'input',[
            'type' => 'password',
            'required' => '',
            'constraints'=>[
                'notBlank',
            ],
        ]);
        $this->addElement('submit', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Přihlásit se');
    }
}