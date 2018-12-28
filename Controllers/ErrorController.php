<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6.10.18
 * Time: 12:46
 */

class ErrorController extends Controller
{
    /**
     * @param $parameters
     */
    public function er404Action($parameters)
    {
        $this->checkParametersMaxCount($parameters, 0);

        header('HTTP/1.0 404 Not Found');

        $this->head['title'] = 'Chyba 404';

        $this->view = 'error';
    }
}