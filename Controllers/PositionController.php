<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 30.12.18
 * Time: 13:40
 */

class PositionController extends Controller
{
    public function listAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }
        $positionManager = new PositionManager();
        $positions = $positionManager->getAll($this->db, 'id_position', 'ASC');
        $positionTable = new PositionTable($positions, 'position');
        $positionTable->build();

        $this->head = [
            'title' => 'Pracovní pozice',
            'keywords' => 'pozice, vytvoř',
            'description' => 'Seznam pracovních pozic',
        ];

        $this->data['positionTable'] = $positionTable;

        $this->view = '/Position/list';
    }

    public function createAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }
        $positionManager = new PositionManager();
        $positionForm = new PositionForm('create-position-form', 'POST', '/position/create');
        $positionForm->build();

        $positionForm->addElement('submit-create', '', 'input',[
            'type' => 'submit',
        ], 'Vytvořit');

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $messages = [];
            $positionForm->setValues([$_POST['name']]);
            if($positionForm->isValid()){
                $positionManager->createPosition(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                    ]);
                $messages = ['Pozice byla úspěšně vytvořena'];
            }else{
                $messages = $positionForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('Position/create',true, 303);
        }

        $this->head = [
            'title' => 'Vytvoř pozici',
            'keywords' => 'pozice, vytvoř',
            'description' => 'Vytvoř pracovní pozici',
        ];

        $this->data['positionForm'] = $positionForm;
        $this->data['header'] = 'Vytvořit pracovní pozici';

        $this->view = '/Position/form';
    }

    public function editAction($parameters){
        $this->checkParametersMaxCount($parameters, 1);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }

        $positionManager = new PositionManager();
        $positionId = $parameters[0];
        $position = $positionManager->getById($this->db,$positionId);
        if (!$position) {
            $this->redirect('error/er404');
        }

        $positionForm = new PositionForm('position-form','post','/position/edit/'.$positionId);
        $positionForm->build();
        $positionForm->addElement('submit-edit', '', 'input',[
            'type' => 'submit',
        ], 'Upravit');
        $positionForm->setValues([
            $position['name'],
        ]);

        $this->data['messages'] = [];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $positionForm->setValues([$_POST['name']]);
            $messages = [];
            if($positionForm->isValid()){
                $positionManager->editPosition(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        $positionId,
                    ]);
                $messages  = ['Pozice byla úspěšně editována'];
            }else{
                $messages  = $positionForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('position/edit/'.$positionId,true, 303);
        }

        $this->head = [
            'title' => 'Upravit pozici',
            'keywords' => 'pozice, vytvoř',
            'description' => 'Formulář pro editaci pracovní pozice',
        ];

        $this->data['positionForm'] = $positionForm;
        $this->data['header'] = 'Upravit pracovní pozici';

        $this->view = 'Position/form';
    }

    /**
     * @param $parameters
     */
    public function deleteAction($parameters)
    {
        if(isset($_SESSION['user'])){
            if($_SESSION['user']['position']!=0){
                $this->redirect('home/index');
            }
            $this->checkParametersMaxCount($parameters, 1);

            if (empty($parameters[0])){
                $this->redirect('error/er404');
            }

            $positionManager = new PositionManager();

            $positionManager->deleteById($this->db, $parameters[0]);

            $this->redirect('position/list');
        }else{
            $this->redirect('home/index');
        }
    }
}