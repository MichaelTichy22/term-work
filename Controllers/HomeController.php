<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6.10.18
 * Time: 13:01
 */

class HomeController extends Controller
{
    /**
     * @param $parameters
     */
    public function indexAction($parameters)
    {
        $this->checkParametersMaxCount($parameters,1);

        if (!$_SESSION['user']){
            $this->redirect('user/login/');
        }

        $postManager = new PostManager();
        $posts = $postManager->getAll($this->db,'create_date', 'DESC');

        $this->head = [
            'title' => 'Domovská stránka',
            'keywords' => 'úvod, list',
            'description' => 'Úvodní stránka',
        ];

        $this->data['posts'] = $posts;

        $this->view = 'home';
    }
}