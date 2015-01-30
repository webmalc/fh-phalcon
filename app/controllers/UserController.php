<?php
namespace FH\Controllers;

/**
 * Users controller class
 */
class UserController extends ControllerBase
{
    /**
     * Login form action
     * @return \Phalcon\Http\Response
     */
    public function loginAction()
    {
        if ($this->di->get('auth')->getUser()) {
            return $this->error404();
        }
    }

    /**
     * Profile action
     * @return \Phalcon\Http\Response
     */
    public function profileAction()
    {
        $this->onlyAjax();
    }

}