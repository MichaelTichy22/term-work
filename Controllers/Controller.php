<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6.10.18
 * Time: 14:41
 */

abstract class Controller
{
    protected $view ='';
    protected $data = [];
    protected $head = ['title'=>'', 'keywords'=> '', 'description'=>''];
    protected $db;

    public function __construct()
    {
        $this->db = new DatabaseManager('db', 'root', 'docker', 'term_work');
        $this->db->connect();
    }

    public function renderView()
    {
        if ($this->view)
        {
            extract($this->data);
            require ('Views/'.$this->view.'.phtml');
        }
    }

    /**
     * @param $url
     */
    public function redirect($url, $parameter1 = null, $parameter2 = null)
    {
        if (empty($parameter1) || empty($parameter2)){
            header('Location: /'.$url);
        }else{
            header('Location: /'.$url,  $parameter1,  $parameter2);
        }
        header('Connection: close');
        exit;
    }

    /**
     * @param $parameters
     * @param $max
     */
    public function checkParametersMaxCount($parameters, $max)
    {
        $count = count($parameters);
        if(empty($parameters[$count-1])){
            $count--;
        }
        if ($count>$max){
            $this->redirect('error/er404');
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getHead()
    {
        return $this->head;
    }

}