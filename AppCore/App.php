<?php

require 'Router.php';

class App
{
    public static function run(){
        mb_internal_encoding("UTF-8");
        date_default_timezone_set("Europe/Prague");
        session_start();
        function autoloadFunction($class){
            if(preg_match('/Controller$/',$class)) {
                require('Controllers/'.$class.'.php');
            }else if (preg_match('/Manager$/',$class)) {
                require('Managers/'.$class.'.php');
            }else if (preg_match('/Form$/',$class)){
                require('Forms/'.$class.'.php');
            }else if (preg_match('/Table$/',$class)){
                require('Tables/'.$class.'.php');
            }else{
                require('Libs/'.$class.'.php');
            }
        }

        spl_autoload_register('autoloadFunction');
        $router = new Router();
        $controller = $router->getController();

        $head = $controller->getHead();
        $data = $controller->getData();


        require ('Views/base.phtml');

        unset($_SESSION['message']);
    }

}