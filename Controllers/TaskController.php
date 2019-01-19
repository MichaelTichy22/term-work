<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 16:22
 */

class TaskController extends Controller
{
    public function listAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] < 1){
                $this->redirect('home/index/');
            }
        }
        $importForm = new ImportJsonForm('import-json', 'post', 'file/import/task', 'multipart/form-data');
        $importForm->build();

        $taskManager = new TaskManager();
        $task = $taskManager->getAll($this->db, 'id_task', 'ASC');
        $taskTable = new TaskTable($task, 'task');
        $taskTable->build();

        $this->head = [
            'title' => 'Úkoly',
            'keywords' => 'úkoly, seznam',
            'description' => 'Seznam úkolů',
        ];

        $this->data['taskTable'] = $taskTable;
        $this->data['importForm'] = $importForm;

        $this->view = '/Task/list';
    }

    public function createAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }

        $taskManager = new TaskManager();
        $taskForm = new TaskForm('create-task-form', 'POST', '/task/create');
        $taskForm->build($this->db);

        $taskForm->addElement('submit-create', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Vytvořit');

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $messages = [];
            $taskForm->setValues([$_POST['name'], $_POST['description'], $_POST['orders'], $_POST['users']]);
            if($taskForm->isValid()){
                $taskManager->createTask(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        htmlspecialchars($_POST['description']),
                        $_POST['orders'],
                        $_POST['users'],
                    ]);
                $messages = ['Úkol byl úspěšně vytvořen'];
            }else{
                $messages = $taskForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('Task/create',true, 303);
        }

        $this->head = [
            'title' => 'Vytvoř úkol',
            'keywords' => 'úkol, vytvoř',
            'description' => 'Vytvoř úkol',
        ];

        $this->data['taskForm'] = $taskForm;
        $this->data['header'] = 'Vytvořit úkol';

        $this->view = '/Task/form';
    }

    public function editAction($parameters){
        $this->checkParametersMaxCount($parameters, 1);

        if (!$_SESSION['user']){
            if ($_SESSION['role'] != 2){
                $this->redirect('home/index/');
            }
        }

        $taskManager = new TaskManager();
        $taskId = $parameters[0];
        $task = $taskManager->getById($this->db,$taskId);
        if (!$task) {
            $this->redirect('error/er404');
        }

        $taskForm = new TaskForm('order-form','post','/task/edit/'.$taskId);
        $taskForm->build($this->db);
        $taskForm->addElement('submit-edit', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Upravit');
        $taskForm->setValues([
            $task['name'],
            $task['description'],
            $task['id_order'],
            $task['id_user']
        ]);

        $this->data['messages'] = [];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $taskForm->setValues([$_POST['name'], $_POST['description'], $_POST['orders'], $_POST['users']]);
            $messages = [];
            if($taskForm->isValid()){
                $taskManager->editTask(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        htmlspecialchars($_POST['description']),
                        $_POST['orders'],
                        $_POST['users'],
                        $taskId,
                    ]);
                $messages  = ['Úkol byl úspěšně upraven'];
            }else{
                $messages  = $taskForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('task/edit/'.$taskId,true, 303);
        }

        $this->head = [
            'title' => 'Upravit úkol',
            'keywords' => 'úkol, editovat',
            'description' => 'Formulář pro úpravu úkolu',
        ];

        $this->data['taskForm'] = $taskForm;
        $this->data['header'] = 'Upravit úkol';

        $this->view = 'Task/form';
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

            $orderManager = new OrderManager();

            $orderManager->deleteById($this->db, $parameters[0]);

            $this->redirect('order/list');
        }else{
            $this->redirect('home/index');
        }
    }
}