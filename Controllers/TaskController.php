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

        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] != 2){
                $this->redirect('home/index/');
            }
        } else {
            $this->redirect('user/login/');
        }

        $taskManager = new TaskManager();
        $task = $taskManager->getAll($this->db, 'state', 'ASC');
        $taskTable = new TaskTable($task, 'task');
        $taskTable->build();

        $this->head = [
            'title' => 'Úkoly',
            'keywords' => 'úkoly, seznam',
            'description' => 'Seznam úkolů',
        ];

        $this->data['taskTable'] = $taskTable;
        $this->data['title'] = 'Úkoly';

        $this->view = '/Task/list';
    }

    public function myListAction($parameters){
        $this->checkParametersMaxCount($parameters, 0);

        if (!isset($_SESSION['user'])) {
            $this->redirect('user/login/');
        }

        $taskManager = new TaskManager();
        $tasks = $taskManager->getAllByUser($this->db, 'state', 'ASC', $_SESSION['user']['id_user']);

        $tasks0 = [];
        $tasks1 = [];
        $tasks3 = [];

        foreach ($tasks as $task) {
            if ($task['state'] === '0') {
                $tasks0[] = $task;
            } else if($task['state'] === '1'){
                $tasks1[] = $task;
            } else {
                $tasks2[] = $task;
            }
        }

        $taskTable0 = new TaskTable($tasks0, 'task');
        $taskTable0->build();

        $taskTable1 = new TaskTable($tasks1, 'task');
        $taskTable1->build();

        $taskTable2 = new TaskTable($tasks2, 'task');
        $taskTable2->build();

        $this->head = [
            'title' => 'Moje úkoly',
            'keywords' => 'úkoly, seznam',
            'description' => 'Seznam úkolů',
        ];

        $this->data['taskTable0'] = $taskTable0;
        $this->data['taskTable1'] = $taskTable1;
        $this->data['taskTable2'] = $taskTable2;
        $this->data['title'] = 'Moje úkoly';

        $this->view = '/Task/list';
    }

    public function detailAction($parameters)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 0){
                $this->redirect('home/index/');
            }
        }

        $this->checkParametersMaxCount($parameters, 1);

        if (empty($parameters[0])){
            $this->redirect('error/er404');
        }

        $taskManager = new TaskManager();
        $taskId = $parameters[0];
        $task = $taskManager->getById($this->db,$taskId);
        if (!$task) {
            $this->redirect('error/er404');
        }

        if ($task['id_user'] != $_SESSION['user']['id_user']) {
            $this->redirect('home/index/');
        }

        $taskActionForm = new TaskActionForm('order-form','post','/task/detail/'.$taskId);
        $taskActionForm->build($this->db);

        $taskActionForm->setValues([
            0,
            $task['state'],
        ]);

        $this->data['messages'] = [];

        if($_SERVER['REQUEST_METHOD']=='POST'){

            $taskActionForm->setValues([$_POST['hours'], $_POST['state']]);
            $messages = [];
            if($taskActionForm->isValid()){
                $hours = '0';
                if ($task['state'] === '1') {
                    $hours = $_POST['hours'];
                }
                $userManager = new UserManager();
                $hoursWorked = $userManager->getById($this->db, $_SESSION['user']['id_user'])['hours_worked'];
                $userManager->addHours(
                    $this->db, [
                        (intval($hoursWorked) + intval($hours)),
                        $_SESSION['user']['id_user'],
                    ]
                );

                $taskManager->editTaskHoursState(
                    $this->db,
                    [
                        (intval($task['hours_done']) + intval($hours)),
                        $_POST['state'],
                        $taskId,
                    ]);

                $messages  = ['Úkol byl úspěšně upraven'];
            }else{
                $messages  = $taskActionForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('task/detail/'.$taskId,true, 303);
        }

        $this->head = [
            'title' => 'Upravit úkol',
            'keywords' => 'úkol, editovat',
            'description' => 'Formulář pro úpravu úkolu',
        ];

        $this->data['taskActionForm'] = $taskActionForm;
        $this->data['id'] = $task['id_task'];
        $this->data['name'] = $task['name'];
        $this->data['description'] = $task['description'];
        $this->data['hours'] = $task['hours_done'];
        $this->data['header'] = 'Upravit úkol';

        $this->view = 'Task/show';
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

        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] != 2){
                $this->redirect('home/index/');
            }
        } else {
            $this->redirect('user/login/');
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
            if ($task['id_user'] == $_POST['users']) {
                $state = $task['state'];
            } else{
                $state = 0;
            }
            $messages = [];
            if($taskForm->isValid()){
                $taskManager->editTask(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        htmlspecialchars($_POST['description']),
                        $_POST['orders'],
                        $_POST['users'],
                        $state,
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

            $taskManager = new TaskManager();

            $taskManager->deleteById($this->db, $parameters[0]);

            $this->redirect('task/list');
        }else{
            $this->redirect('home/index');
        }
    }

    public function changeStateAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 2);

        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] == 0){
                $this->redirect('home/index/');
            }
        } else {
            $this->redirect('user/login/');
        }

        $taskManager = new TaskManager();
        $taskId = $parameters[0];
        $state = $parameters[1];
        $task = $taskManager->getById($this->db,$taskId);
        if (!$task) {
            $this->redirect('error/er404');
        }

        $this->data['messages'] = [];

        $taskManager->editTaskState(
        $this->db, [
            $state,
            $taskId,
        ]);

        if ($state === '1') {
            $messages  = ['Úkol byl přijat'];
        } else {
            $messages  = ['Úkol byl odmítnut'];
        }

        $_SESSION['message'] = $messages;

        $this->redirect('task/my-list');
    }
}