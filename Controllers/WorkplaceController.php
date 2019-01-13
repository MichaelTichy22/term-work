<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 13:32
 */

class WorkplaceController extends Controller
{
    public function listAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] < 1){
                $this->redirect('home/index/');
            }
        }
        $workplaceManager = new WorkplaceManager();
        $workplaces = $workplaceManager->getAll($this->db, 'id_workplace', 'ASC');
        $workplaceTable = new WorkplaceTable($workplaces, 'workplace');
        $workplaceTable->build();

        $this->head = [
            'title' => 'Oddělení',
            'keywords' => 'oddělení, seznam',
            'description' => 'Seznam oddělení',
        ];

        $this->data['workplaceTable'] = $workplaceTable;

        $this->view = '/Workplace/list';
    }

    public function createAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }
        $workplaceManager = new WorkplaceManager();
        $workplaceForm = new WorkplaceForm('create-workplace-form', 'POST', '/workplace/create');
        $workplaceForm->build();

        $workplaceForm->addElement('submit-create', '', 'input',[
            'type' => 'submit',
        ], 'Vytvořit');

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $messages = [];
            $workplaceForm->setValues([$_POST['name']]);
            if($workplaceForm->isValid()){
                $workplaceManager->createWorkplace(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                    ]);
                $messages = ['Odděleni bylo úspěšně vytvořena'];
            }else{
                $messages = $workplaceForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('Workplace/create',true, 303);
        }

        $this->head = [
            'title' => 'Vytvoř oddělení',
            'keywords' => 'oddělení, vytvoř',
            'description' => 'Vytvoř oddělení',
        ];

        $this->data['workplaceForm'] = $workplaceForm;
        $this->data['header'] = 'Vytvořit oddělení';

        $this->view = '/Workplace/form';
    }

    public function editAction($parameters){
        $this->checkParametersMaxCount($parameters, 1);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }

        $workplaceManager = new WorkplaceManager();
        $workplaceId = $parameters[0];
        $workplace = $workplaceManager->getById($this->db,$workplaceId);
        if (!$workplace) {
            $this->redirect('error/er404');
        }

        $workplaceForm = new WorkplaceForm('workplace-form','post','/workplace/edit/'.$workplaceId);
        $workplaceForm->build();
        $workplaceForm->addElement('submit-edit', '', 'input',[
            'type' => 'submit',
        ], 'Upravit');
        $workplaceForm->setValues([
            $workplace['name'],
        ]);

        $this->data['messages'] = [];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $workplaceForm->setValues([$_POST['name']]);
            $messages = [];
            if($workplaceForm->isValid()){
                $workplaceManager->editWorkplace(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        $workplaceId,
                    ]);
                $messages  = ['Oddělení bylo úspěšně upraveno'];
            }else{
                $messages  = $workplaceForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('workplace/edit/'.$workplaceId,true, 303);
        }

        $this->head = [
            'title' => 'Upravit oddělené',
            'keywords' => 'oddělené, uprav',
            'description' => 'Formulář pro úpravu oddělení',
        ];

        $this->data['workplaceForm'] = $workplaceForm;
        $this->data['header'] = 'Upravit oddělení';

        $this->view = 'Workplace/form';
    }

    /**
     * @param $parameters
     */
    public function deleteAction($parameters)
    {
        if(isset($_SESSION['user'])){
            if($_SESSION['user']['role']!=2){
                $this->redirect('home/index');
            }
            $this->checkParametersMaxCount($parameters, 1);

            if (empty($parameters[0])){
                $this->redirect('error/er404');
            }

            $workplaceManager = new WorkplaceManager();

            $workplaceManager->deleteById($this->db, $parameters[0]);

            $this->redirect('workplace/list');
        }else{
            $this->redirect('home/index');
        }
    }
}