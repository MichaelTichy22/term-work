<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 9.10.18
 * Time: 10:08
 */

class PostForm extends Form
{

    /**
     * @param DatabaseManager|null $db
     */
    public function build(DatabaseManager $db = null)
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->getAll($db,'id','ASC');

        $this->addElement('title', 'Titulek', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'shorterThan255',
                'notBlank',
            ],
        ]);
        $this->addElement('category', 'Kategorie', 'select',[
            'options'=>$this->getSelectOptions($categories, 'id', 'name'),
            'required' => 'required',
            'constraints' => [
                'notNull',
            ],
        ]);
        $this->addElement('annotation', 'Anotace', 'textArea',[
            'rows' => '4',
            'cols' => '80',
            'required' => '',
            'constraints' => [
                'shorterThan65535',
                'notBlank',
            ],
        ]);
        $this->addElement('content', 'Obsah', 'textArea',[
            'rows' => '20',
            'cols' => '80',
            'required' => '',
            'constraints' => [
                'shorterThan65535',
                'notBlank',
            ],
        ]);

    }

}