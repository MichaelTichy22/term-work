<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 20.1.19
 * Time: 14:58
 */

class TaskActionForm extends Form
{
    public function build(DatabaseManager $db = null)
    {
        $this->addElement('hours','Přidat hodiny', 'input', [
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'justNumbers',
                'notBlank',
            ],
        ]);
        $this->addElement('state', 'Stav', 'select', [
            'options'=> [
                [
                    'value'=> '0',
                    'name' => 'Nepřijat',
                    'selected'=> ''
                ], [
                    'value'=> '1',
                    'name' => 'Přijat',
                    'selected'=> ''
                ], [
                    'value'=> '2',
                    'name' => 'Dokončen',
                    'selected'=> ''
                ]
            ],
            'required' => '',
        ]);
        $this->addElement('submit', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Upravit');
    }
}