<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 8.10.18
 * Time: 21:50
 */

class Router
{
    /**
     * @return Controller
     */
    public function getController(){
        $parsedURL = $this->getParsedURL($_SERVER['REQUEST_URI']);

        if(empty($parsedURL[0]) || ($parsedURL[0] === 'home' && empty($parsedURL[1]))){
            $this->redirect('home/index');
        }else {
            $controllerName = str_replace('-', ' ', array_shift($parsedURL));
            $controllerName = str_replace(' ', '', ucwords($controllerName));
        }
        if(empty($parsedURL[0])){
            $this->redirect('error/er404');
        }else{
            $controllerAction = str_replace('-', ' ', array_shift($parsedURL));
            $controllerAction = str_replace(' ', '',ucwords($controllerAction));
        }
        $controllerName = $controllerName.'Controller';
        $controllerAction = $controllerAction.'Action';

        if (file_exists('Controllers/'.$controllerName.'.php')){
            $controller = new $controllerName;
            if (method_exists($controller, $controllerAction)){
                $controller->$controllerAction($parsedURL);
            }else{
                $this->redirect('error/er404');
            }
        }
        else{
            $this->redirect('error/er404');
        }

        return $controller;
    }

    /**
     * @param $url
     * @return array|mixed
     */
    private function getParsedURL($url){
        $parsedURL = parse_url($url);
        $parsedURL['path'] = ltrim($parsedURL['path'],'/');
        $parsedURL['path'] = rtrim($parsedURL['path']);
        $parsedURL = explode('/',$parsedURL['path']);

        return $parsedURL;
    }

    /**
     * @param $url
     */
    private function redirect($url){
        header('Location: /'.$url);
        header('Connection: close');
        exit;
    }
}