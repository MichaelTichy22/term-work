<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19.1.19
 * Time: 18:41
 */

class FileController extends Controller
{
    public function downloadAction($parameters) {
        if (!$_SESSION['user']){
            $this->redirect('user/login/');
        }

        $this->checkParametersMaxCount($parameters,1);

        $entityManager = $this->getManager($parameters[0]);
        $entities = $entityManager->getAll($this->db, 'id_'.$parameters[0], 'ASC');

        $result[$parameters[0]] = $entities;

        $fp = fopen($parameters[0].'.json', 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);

        header('Content-disposition: attachment; filename="'.$parameters[0].'.json"');
        header('Content-type: application/json');
        readfile($parameters[0].'.json');
        unlink($parameters[0].'.json');
        die();
    }

    public function importAction($parameters) {
        if ($_SESSION['user']){
            if ($_SESSION['user']['role'] != 2) {
                $this->redirect('user/login/');
            }
        } else {
            $this->redirect('user/login/');
        }

        $this->checkParametersMaxCount($parameters,1);

        $file = $_FILES['upload_file']['name'];
        $messages = [];

        $ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));

        if (isset($_POST['submit'])) {
            if ($ext === 'json') {
                $json = file_get_contents($_FILES['upload_file']['tmp_name']);
                $data = json_decode($json, TRUE);

                if (key($data) != $parameters[0]) {
                    $messages = ['Soubor nemá správný formát!'];

                    $_SESSION['message'] = $messages;

                    $this->redirect($parameters[0].'/list');
                }

                foreach ($data[$parameters[0]] as $entity) {
                    switch (key($data)){
                        case 'order':
                            $this->addOrder($entity);
                            break;
                        case 'workplace':
                            $this->addWorkplace($entity);
                            break;
                        default:
                            $this->redirect('error/er404');
                            break;
                    }
                }

                $messages = ['Soubor byl úspěšně importován!'];
            }else {
                $messages = ['Soubor musí být typu JSON!'];
            }
        }
        $_SESSION['message'] = $messages;

        $this->redirect($parameters[0].'/list');
    }

    private function getManager($name) {
        $managers = [
            'user' => new UserManager(),
            'post' => new PostManager(),
            'order' => new OrderManager(),
            'task' => new TaskManager(),
            'position' => new PositionManager(),
            'tag' => new TagManager(),
            'workplace' => new WorkplaceManager(),
        ];

        return $managers[$name];
    }

    private function addOrder($order) {
        $orderManager = new OrderManager();

        $orderManager->createOrder($this->db, [
            htmlspecialchars($order['name']),
            htmlspecialchars($order['description']),
        ]);
    }

    private function addWorkplace($workplace) {
        $workplaceManager = new WorkplaceManager();

        $workplaceManager->createWorkplace($this->db, [
            htmlspecialchars($workplace['name']),
        ]);
    }
}