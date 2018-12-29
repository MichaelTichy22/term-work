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
        $postManager = new PostManager();
        $posts = $postManager->getAll($this->db,'create_date', 'DESC');
//        $postTable = new PostTable($posts);
//        $postTable->build();

        $this->head = [
            'title' => 'Domovská stránka',
            'keywords' => 'úvod, list',
            'description' => 'Úvodní stránka',
        ];

//        $this->data['postTable'] = $postTable;

        $this->view = 'home';
    }
}