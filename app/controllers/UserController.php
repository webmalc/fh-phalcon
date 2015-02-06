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

    /**
     * Returns current user as JSON
     * @return \Phalcon\Http\Response
     */
    public function loggedAction()
    {
        $this->onlyAjax();
        $user = $this->di->get('auth')->getUser();

        if (!$user) {
            return $this->error404();
        }

        return $this->jsonResponse($user);
    }

    /**
     * Save current user
     * @return \Phalcon\Http\Response
     */
    public function loggedSaveAction()
    {
        $data = $this->jsonRequest('post');
        $user = $this->di->get('auth')->getUser();

        if (!$user || empty($data)) {
            return $this->error404();
        }
        $user->setData($data);
        if (!empty($data['password'])) {
            $user->setPassword($data['password']);
        }
        if ($user->save()) {

            if (!empty($data['password'])) {
                $this->di->get('mail')->send($user->email, 'New password: ' . $data['password']);
            }
            return $this->jsonResponse([
               'success' => true
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => implode('; ', $user->getMessages())
            ]);
        }

    }

    /**
     * User list action
     * @return \Phalcon\Http\Response
     */
    public function indexAction()
    {

    }
}