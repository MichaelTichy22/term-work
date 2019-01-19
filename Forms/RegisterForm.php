<?php

class RegisterForm extends Form
{

    /**
     * @param DatabaseManager|null $db
     */
    function build(DatabaseManager $db = null)
    {
        $this->addElement('username', 'Uživatelské jméno', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'shorterThan32',
                'longerThan4',
                'notBlank',
                'noSpecialChars',
            ],
        ]);
        $this->addElement('email', 'Email', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'isEmail',
                'notBlank',
                'shorterThan255',
            ],
        ]);
        $this->addElement('password', 'Heslo', 'input',[
            'type' => 'password',
            'required' => '',
            'constraints' => [
                'notBlank',
                'shorterThan32',
            ],
        ]);
        $this->addElement('name', 'Jméno', 'input',[
                'type' => 'text',
                'required' => '',
                'constraints' => [
                    'shorterThan32',
                    'notBlank',
                    'noSpecialChars',
                ],
            ]
        );
        $this->addElement('surname', 'Příjmení', 'input',[
                'type' => 'text',
                'required' => '',
                'constraints' => [
                    'shorterThan32',
                    'notBlank',
                    'noSpecialChars',
                ],
            ]
        );
        $this->addElement('register', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Zaregistrovat');
    }
}