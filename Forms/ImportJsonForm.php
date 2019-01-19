<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19.1.19
 * Time: 20:11
 */

class ImportJsonForm extends Form
{
   public function build(DatabaseManager $db = null)
   {
       $this->addElement('submit', '', 'input',[
           'type' => 'submit',
           'class' => 'btn-blue',
       ], 'Importovat JSON');

       $this->addElement('upload_file', null, 'input', [
           'type' => 'file',
           'constraints' => [
               'notNull',
           ],
       ]);
   }
}