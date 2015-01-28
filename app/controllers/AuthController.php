<?php
namespace FH\Controllers;
/**
 * Authentication controller class
 */
class AuthController extends ControllerBase
{
    /**
     * Login action
     * @return \Phalcon\Http\Response
     */
    public function loginAction()
    {
        $data = $this->jsonRequest('post');
        if ($this->di->get('auth')->isLogged() || empty($data) || empty($data['email']) || empty($data['password'])) {
            return $this->error404();
        }
        try {
            $this->di->get('auth')->check(
                filter_var($data['email'], FILTER_SANITIZE_EMAIL),
                $data['password'],
                (!empty($data['remember']) && $data['remember']) ? true : false
            );
            return $this->jsonResponse([
                'success' => true
            ]);
        } catch (\Exception $e) {
            $message = 'Oops! Something went wrong.';
            if ($this->di->get('config')->environment->type == 'dev') {
                $message = $e->getMessage();
            }
            return $this->jsonResponse([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    /**
     * Logout action
     * @return \Phalcon\Http\Response
     */
    public function logoutAction()
    {
        if (!$this->di->get('auth')->isLogged()) {
            return $this->error404();
        }
        $this->di->get('auth')->logout();

        return $this->response->redirect();
    }

    /**
     * Reset password action
     * @return \Phalcon\Http\Response
     */
    public function remindAction()
    {
        $data = $this->jsonRequest('post');
        if ($this->di->get('auth')->isLogged() || empty($data) || empty($data['email'])) {
            return $this->error404();
        }
        try {
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $this->di->get('auth')->resetPassword($email);
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Yes! A new password sent to you.'
            ]);
        } catch (\Exception $e) {
            $message = 'Oops! Something went wrong.';
            if ($this->di->get('config')->environment->type == 'dev') {
                $message = $e->getMessage();
            }
            return $this->jsonResponse([
                'success' => false,
                'message' => $message
            ]);
        }
    }
}