<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 13.1.19
 * Time: 19:09
 */

class TagController extends Controller
{
    public function listAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] != 2){
                $this->redirect('home/index/');
            }
        } else {
            $this->redirect('user/login/');
        }

        $tagManager = new TagManager();
        $tags = $tagManager->getAll($this->db, 'id_tag', 'ASC');
        $tagTable = new TagTable($tags, 'tag');
        $tagTable->build();

        $this->head = [
            'title' => 'Štítky',
            'keywords' => 'štítky, seznam',
            'description' => 'Seznam štítků',
        ];

        $this->data['tagTable'] = $tagTable;

        $this->view = '/Tag/list';
    }

    public function createAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] != 2){
                $this->redirect('home/index/');
            }
        } else {
            $this->redirect('user/login/');
        }

        $tagManager = new TagManager();
        $tagForm = new TagForm('create-tag-form', 'POST', '/tag/create');
        $tagForm->build();

        $tagForm->addElement('submit-create', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Vytvořit');

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $messages = [];
            $tagForm->setValues([$_POST['name']]);
            if($tagForm->isValid()){
                $tagManager->createTag(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                    ]);
                $messages = ['Štítek byl úspěšně vytvořen'];
            }else{
                $messages = $tagForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('Tag/create',true, 303);
        }

        $this->head = [
            'title' => 'Vytvoř štítke',
            'keywords' => 'štítky, vytvoř',
            'description' => 'Vytvoř štítek',
        ];

        $this->data['tagForm'] = $tagForm;
        $this->data['header'] = 'Vytvořit štítek';

        $this->view = '/Tag/form';
    }

    public function editAction($parameters){
        $this->checkParametersMaxCount($parameters, 1);

        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] != 2){
                $this->redirect('home/index/');
            }
        } else {
            $this->redirect('user/login/');
        }

        $tagManager = new TagManager();
        $tagId = $parameters[0];
        $tag = $tagManager->getById($this->db,$tagId);
        if (!$tag) {
            $this->redirect('error/er404');
        }

        $tagForm = new TagForm('tag-form','post','/tag/edit/'.$tagId);
        $tagForm->build();
        $tagForm->addElement('submit-edit', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Upravit');
        $tagForm->setValues([
            $tag['name'],
        ]);

        $this->data['messages'] = [];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $tagForm->setValues([$_POST['name']]);
            $messages = [];
            if($tagForm->isValid()){
                $tagManager->editTag(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        $tagId,
                    ]);
                $messages  = ['Štítek byl úspěšně upraven'];
            }else{
                $messages  = $tagForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('tag/edit/'.$tagId,true, 303);
        }

        $this->head = [
            'title' => 'Upravit štítek',
            'keywords' => 'štítek, uprav',
            'description' => 'Formulář pro úpravu štíteků',
        ];

        $this->data['tagForm'] = $tagForm;
        $this->data['header'] = 'Upravit štítek';

        $this->view = 'Tag/form';
    }

    /**
     * @param $parameters
     */
    public function deleteAction($parameters)
    {
        if(isset($_SESSION['user'])){
            if($_SESSION['user']['role'] != 2){
                $this->redirect('home/index');
            }
            $this->checkParametersMaxCount($parameters, 1);

            if (empty($parameters[0])){
                $this->redirect('error/er404');
            }

            $tagManager = new TagManager();

            $tagManager->deleteById($this->db, $parameters[0]);

            $this->redirect('tag/list');
        }else{
            $this->redirect('home/index');
        }
    }
}