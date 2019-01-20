<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.12.18
 * Time: 15:29
 */

class OrderController extends Controller
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

        $importForm = new ImportJsonForm('import-json', 'post', 'file/import/order', 'multipart/form-data');
        $importForm->build();

        $orderManager = new OrderManager();
        $order = $orderManager->getAll($this->db, 'id_order', 'ASC');
        $orderTable = new OrderTable($order, 'order');
        $orderTable->build();

        $this->head = [
            'title' => 'Zakázky',
            'keywords' => 'zakázky, seznam',
            'description' => 'Seznam zakázek',
        ];

        $this->data['orderTable'] = $orderTable;
        $this->data['importForm'] = $importForm;

        $this->view = '/Order/list';
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

        $orderManager = new OrderManager();
        $orderForm = new OrderForm('create-order-form', 'POST', '/order/create');
        $orderForm->build();

        $orderForm->addElement('submit-create', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Vytvořit');

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $messages = [];
            $orderForm->setValues([$_POST['name'], $_POST['description']]);
            if($orderForm->isValid()){
                $orderManager->createOrder(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        htmlspecialchars($_POST['description']),
                    ]);
                $messages = ['Zakázka byla úspěšně vytvořena'];
            }else{
                $messages = $orderForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('Order/create',true, 303);
        }

        $this->head = [
            'title' => 'Vytvoř zakázku',
            'keywords' => 'zakázka, vytvoř',
            'description' => 'Vytvoř zakázku',
        ];

        $this->data['orderForm'] = $orderForm;
        $this->data['header'] = 'Vytvořit zakázku';

        $this->view = '/Order/form';
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

        $orderManager = new OrderManager();
        $orderId = $parameters[0];
        $order = $orderManager->getById($this->db,$orderId);
        if (!$order) {
            $this->redirect('error/er404');
        }

        $orderForm = new OrderForm('order-form','post','/order/edit/'.$orderId);
        $orderForm->build();
        $orderForm->addElement('submit-edit', '', 'input',[
            'type' => 'submit',
            'class' => 'btn-blue',
        ], 'Upravit');
        $orderForm->setValues([
            $order['name'],
            $order['description'],
        ]);

        $this->data['messages'] = [];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $orderForm->setValues([$_POST['name'], $_POST['description']]);
            $messages = [];
            if($orderForm->isValid()){
                $orderManager->editOrder(
                    $this->db,
                    [
                        htmlspecialchars($_POST['name']),
                        htmlspecialchars($_POST['description']),
                        $orderId,
                    ]);
                $messages  = ['Zakázka byla úspěšně upravena'];
            }else{
                $messages  = $orderForm->getMessages();
            }

            $_SESSION['message'] = $messages;
            $this->redirect('order/edit/'.$orderId,true, 303);
        }

        $this->head = [
            'title' => 'Upravit zakázku',
            'keywords' => 'zakázka, vytvoř',
            'description' => 'Formulář pro úpravu zakázky',
        ];

        $this->data['orderForm'] = $orderForm;
        $this->data['header'] = 'Upravit zakázku';

        $this->view = 'Order/form';
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