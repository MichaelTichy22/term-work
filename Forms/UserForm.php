<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19.1.19
 * Time: 12:31
 */

class UserForm extends Form
{
    public function build(DatabaseManager $db = null)
    {
        $positionManager = new PositionManager();
        $workplaceManager = new WorkplaceManager();

        $positions = $positionManager->getAll($db, 'name', 'ASC');
        $workplaces = $workplaceManager->getAll($db, 'name', 'ASC');

        $this->addElement('email', 'Email', 'input', [
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'isEmail',
                'notBlank',
                'shorterThan255',
            ],
        ]);
//        $this->addElement('password', 'Heslo', 'input', [
//            'type' => 'password',
//            'required' => '',
//            'constraints' => [
//                'notBlank',
//                'shorterThan32',
//            ],
//        ]);
        $this->addElement('name', 'Jméno', 'input', [
                'type' => 'text',
                'required' => '',
                'constraints' => [
                    'shorterThan32',
                    'notBlank',
                    'noSpecialChars',
                ],
            ]
        );
        $this->addElement('surname', 'Příjmení', 'input', [
                'type' => 'text',
                'required' => '',
                'constraints' => [
                    'shorterThan32',
                    'notBlank',
                    'noSpecialChars',
                ],
            ]
        );
        $this->addElement('wage', 'Plat na hodinu', 'input', [
                'type' => 'text',
                'required' => '',
                'constraints' => [
                    'shorterThan32',
                    'notBlank',
                    'justNumbers',
                ],
            ]
        );
        $this->addElement('position', 'Pozice', 'select', [
            'options'=>$this->getSelectOptions($positions, 'id_position', 'name', true),
            'required' => '',
        ]);
        $this->addElement('workplace', 'Oddělení', 'select', [
            'options'=>$this->getSelectOptions($workplaces, 'id_workplace', 'name', true),
            'required' => '',
        ]);
    }
}