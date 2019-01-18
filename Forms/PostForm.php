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
        $tagManager = new TagManager();
        $tags = $tagManager->getAll($db, 'name', 'ASC');

        $this->addElement('title', 'Titulek', 'input',[
            'type' => 'text',
            'required' => '',
            'constraints' => [
                'shorterThan255',
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
        foreach ($tags as $tag){
            $this->addElement('tags[]', $tag['name'], 'input', [
                'type' => 'checkbox',
                'required' => '',
                'checked' => false,
            ], $tag['id_tag']);
        }
    }

}