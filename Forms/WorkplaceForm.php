<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 13:41
 */

class WorkplaceForm extends Form
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