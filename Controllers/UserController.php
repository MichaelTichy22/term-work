<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6.10.18
 * Time: 21:24
 */

class UserController extends Controller
{
    /**
     * @param $parameters
     */
    public function loginAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 0);

        if (isset($_SESSION['user'])){
            $this->redirect('home/index/');
        }

        $userManager = new UserManager();
        $form = new LoginForm('loginForm', 'post','/user/login');
        $form->build($this->db);

        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $messages = [];
            $form->setValues([$_POST['username'],$_POST['password']]);
            $user = $userManager->getByName($this->db,$_POST['username']);

            if(!$form->isValid()){
                $messages = $form->getMessages();
                $this->redirectWithMessages('user/login',$messages);
            }

            if (password_verify($_POST['password'], $user['password'])){
                if ($user['role'] == 0){
                    $messages[] = 'Zadaný uživatelský účet zatím nebyl aktivován!';
                    $this->redirectWithMessages('user/login',$messages);
                }
                $_SESSION['user'] = $user;
                $this->redirect('home/index',true, 303);

            }else{
                $messages[] = 'Špatné uživatelské jméno nebo heslo!';
            }
            $this->redirectWithMessages('user/login',$messages);
        }

        $this->head = [
            'title' => 'Přihlásit se',
            'keywords' => 'přihlášení, uživatel',
            'description' => 'Formulář pro přihlášení',
        ];

        $this->data['form'] = $form;

        $this->view = 'User/login';
    }

    /**
     * @param $parameters
     */
    public function registerAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 0);

        if (isset($_SESSION['user'])){
            $this->redirect('home/index/');
        }
        $form = new RegisterForm('registerForm','post','/user/register');
        $form->build();

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $messages = [];
            $form->setValues([
                $_POST['username'],
                $_POST['email'],
                $_POST['password'],
                $_POST['name'],
                $_POST['surname'],
            ]);
            if(!$form->isValid()){
                $messages = $form->getMessages();
                $this->redirectWithMessages('user/register',$messages);
            }

            $userManager = new UserManager();
            if($userManager->getByName($this->db, $_POST['username']) == null) {
                if ($userManager->getByEmail($this->db, $_POST['email']) == null) {
                    if ($userManager->createUser($this->db,[
                        $_POST['username'],
                        password_hash($_POST['password'], PASSWORD_DEFAULT),
                        $_POST['email'],
                        $_POST['name'],
                        $_POST['surname'],
                    ]) != 0){
                        $messages[] = 'Registrace proběhla úspěšně!';
                    }else {
                        $messages[] = 'Chyba při registraci!';
                    }

                    $_SESSION['message'] = $messages;
                    $this->redirect('user/register',true, 303);
                }else{
                    $messages[] = 'Email '.$_POST['email'].' už je registrovaný!';
                }
            }else{
                $messages[] = 'Uživatelské jméno '.$_POST['username'].' je zabráno';
            }
            $this->redirectWithMessages('user/register',$messages);
        }

        $this->head = [
            'title' => 'Registrace',
            'keywords' => 'registrace, uživatel',
            'description' => 'Formulář pro registraci',
        ];

        $this->data['form'] = $form;

        $this->view = 'User/register';
    }

    /**
     * @param $parameters
     */
    public function logoutAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            $this->redirect('home/index/');
        }

        unset($_SESSION['user']);

        $this->redirect('home/index/');
    }

    /**
     * @param $parameters
     */
    public function listAction($parameters)
    {
        $this->checkParametersMaxCount($parameters, 0);

        if (!$_SESSION['user']){
            $this->redirect('home/index/');
        }

        $userManager = new UserManager();
        $users = $userManager->getAll($this->db, 'surname', 'ASC');
        $userTable = new UserTable($users, 'user');
        $userTable->build();

        $this->head = [
            'title' => 'Seznam zaměstnanců',
            'keywords' => 'seznam, uživatelé, zamestanci',
            'description' => 'Seznam zaměstnanců',
        ];

        $this->data['userTable'] = $userTable;

        $this->view = "User/list";
    }

    public function editAction($parameters) {
        $this->checkParametersMaxCount($parameters, 1);

        if(isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] != 2) {
                $this->redirect('home/index');
            }

            $userManager = new UserManager();
            $userId = $parameters[0];
            $user = $userManager->getById($this->db, $userId);
            if (!$user) {
                $this->redirect('error/er404');
            }

            $form = new UserForm('userForm', 'post', '/user/edit/' . $userId);
            $form->build($this->db);
            $form->addElement('submit-edit', '', 'input', [
                'type' => 'submit',
                'class' => 'btn-blue',
            ], 'Editovat');

            $form->setValues([
                $user['email'],
                $user['name'],
                $user['surname'],
                $user['wage'],
                $user['id_position'],
                $user['id_workplace'],
            ]);

            $this->data['messages'] = [];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $form->setValues([
                    $_POST['email'],
                    $_POST['name'],
                    $_POST['surname'],
                    $_POST['wage'],
                    $_POST['id_position'],
                    $_POST['id_workplace'],
                ]);
                $messages = [];

                if ($form->isValid()) {
                    $userManager->editUser(
                        $this->db,
                        [
                            $_POST['email'],
                            $_POST['name'],
                            $_POST['surname'],
                            $_POST['wage'],
                            $_POST['position'],
                            $_POST['workplace'],
                            $user['id_user'],
                        ]
                    );
                    $messages = ['Uživatel byl úspěšně editován'];
                } else {
                    $messages = $form->getMessages();
                }

                $_SESSION['message'] = $messages;
                $this->redirect('user/edit/' . $userId, true, 303);
            }

            $this->head = [
                'title' => 'Editovat uživatele',
                'keywords' => 'uživatel, editovat, formulář',
                'description' => 'Formulář pro editaci uživatele ',
            ];

            $this->data['form'] = $form;
            $this->data['header'] = 'Editovat uživatele';

            $this->view = 'User/form';

        }else {
            $this->redirect('home/index');
        }
    }

    public function deleteAction($parameters)
    {
        if(isset($_SESSION['user'])){
            if($_SESSION['user']['role']!=2){
                $this->redirect('home/index');
            }

            if ($_SESSION['user']['id_user'] == $parameters[0]) {
                $this->redirect('error/er404');
            }

            $this->checkParametersMaxCount($parameters, 1);

            if (empty($parameters[0])){
                $this->redirect('error/er404');
            }

            $userManager = new UserManager();

            $userManager->deleteById($this->db, $parameters[0]);

            $this->redirect('user/list');
        }else{
            $this->redirect('home/index');
        }
    }

    /**
     * @param $url
     * @param $messages
     */
    private function redirectWithMessages($url, $messages){
        if (isset($_POST['ajax'])){
            foreach ($messages as $message){
                echo nl2br($message."\n");
            }
            exit;
        }else{
            $_SESSION['message'] = $messages;
            $this->redirect($url,true, 303);
        }
    }
}