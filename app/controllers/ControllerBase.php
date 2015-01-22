<?php
namespace FH\Controllers;

use Phalcon\Mvc\Controller;
use \Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{

    /**
     * Execute before the router
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();
        $user = $this->auth->getUser();
        if ($this->acl->isProtected($controllerName, $actionName)) {
            // if user not logged
            if(empty($user)) {
                return $this->response->redirect('user/login');
            }
            // if user not allowed to controller/action
            if(!$this->acl->isAllowed($user, $controllerName, $actionName)) {
                return $this->response->redirect('user/login');
            }
        }
        return true;
    }

    /**
     * Return 404 page
     * @return \Phalcon\Http\Response
     */
    public function error404()
    {
        $this->response->setStatusCode(404, "Not Found");
        $this->response->setContent("Page not found");
        return $this->response;
    }
    /**
     * Return json response
     * @param array|object $data
     * @return \Phalcon\Http\Response
     */
    public function jsonResponse($data)
    {
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($data));
        return $this->response;
    }
    /**
     * Get json request
     * @param string $method
     * @param int $filter
     * @param boolean $ajax
     * @return array|null
     */
    public function jsonRequest($method = 'get', $filter = FILTER_SANITIZE_STRING, $ajax = true)
    {
        $this->view->disable();
        $methodName = 'is' . ucfirst(mb_strtolower($method));
        if (!method_exists($this->request, $methodName) || !$this->request->$methodName()) {
            return null;
        }
        if ($ajax && !$this->request->isAjax()) {
            return null;
        }
        if (strpos($this->request->getServer('CONTENT_TYPE'), 'application/json') === false) {
            return null;
        }
        $data = json_decode($this->request->getRawBody(), true);
        if (empty($data)) {
            return null;
        }
        if (!empty($filter)) {
            $data = array_map('trim', $data);
            $data = filter_var_array($data, $filter);
        }
        return $data;
    }
}
