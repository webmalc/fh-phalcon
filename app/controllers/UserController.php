<?php
namespace FH\Controllers;

use FH\Models\User;
use Phalcon\Http\Response;

/**
 * User controller class
 */
class UserController extends ControllerBase
{
    /**
     * Login form action
     * @return Response
     */
    public function loginAction()
    {
        if ($this->di->get('auth')->getUser()) {
            return $this->error404();
        }
    }

    /**
     * Profile action
     * @return Response
     */
    public function profileAction()
    {
        $this->onlyAjax();
    }

    /**
     * Returns current user as JSON
     * @return Response
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
     * @return Response
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
     * Save user
     * @return Response
     */
    public function saveAction()
    {
        $data = $this->jsonRequest('post');
        if (empty($data['id'])) {
            return $this->error404();
        }
        $user = User::findFirst($data['id']);
        if (empty($user)) {
            return $this->error404();
        }
        $user->setData($data);
        if (!empty($data['role'])) {
           $user->roles = [$data['role']];
        }
        if ($user->save()) {
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
     * Delete user
     * @return Response
     */
    public function deleteAction()
    {
        $data = $this->jsonRequest('post');
        if (empty($data['id'])) {
            return $this->error404();
        }
        $user = User::findFirst($data['id']);
        if (empty($user)) {
            return $this->error404();
        }
        $user->delete();
        
        return $this->jsonResponse([
            'success' => true
        ]);
    }        

    /**
     * User list tpl
     * @return Response
     */
    public function listAction()
    {
        $this->onlyAjax();

        return $this->jsonResponse(
            User::find(['order' => 'createdAt DESC'])
        );
    }
    
    /**
     * User list 
     * @return Response
     */
    public function indexAction()
    {
        $this->view->setVar("security", $this->di->get('security'));
    }
    
    /**
     * User new action
     * @return Response
     */
    public function newAction()
    {
        $data = $this->jsonRequest('post');
        
        if (empty($data)) {
            return $this->error404();
        }
        
        $user = new User;
        $user->setData($data);
        $user->roles = empty($data['roles']) ? null: [$data['roles']];
        $password = $this->di->get('helper')->getToken(6, true, 'lud');
        $user->setPassword($password);
        
        if ($user->save()) {

            $this->di->get('mail')->send($user->email, 'New password: ' . $password);
            
            return $this->jsonResponse([
               'success' => true,
               'message' => 'The user ' . $user->email . ' was successfully created! ' . 
               'Password: ' . $password
            ]);
        } else {
            return $this->jsonResponse([
                'success' => false,
                'message' => implode('; ', $user->getMessages())
            ]);
        }
    }        
}