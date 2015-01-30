<?php

namespace FH\Lib;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
use FH\Models\User;

/**
 * Acl class
 */
class Acl extends Component
{
    /**
     * User role name
     * @const USER_ROLE_NAME
     */
    const ROLE_USER_NAME = 'user';

    /**
     * Admin role name
     * @const ADMIN_ROLE_NAME
     */
    const ROLE_ADMIN_NAME = 'admin';

    /**
     * The ACL Object
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * The filepath of the ACL cache
     * @var string
     */
    private $filePath;

    /**
     * APC cache name for ACL
     * @var string
     */
    private $apcName;


    /**
     * Protected controllers
     * @var array
     */
    private $protectedControllers = [
        self::ROLE_USER_NAME => [
            'index' => ['index'],
            'user' => ['profile']
        ]
    ];

    /**
     * Constructor
     * @param string $path
     * @param string $apc
     */
    public function __construct($path, $apc)
    {
        $this->filePath = $path;
        $this->apcName = $apc;
    }

    /**
     * Check public or not controller/action
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isProtected($controller, $action)
    {
        foreach ($this->protectedControllers as $entry) {
            foreach ($entry as $controllerName => $actions) {
                if ($controller == $controllerName && in_array($action, $actions)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns the ACL list
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        // Check if the ACL is already created
        if (is_object($this->acl)) {
            return $this->acl;
        }

        //In dev environment rebuild acl every request
        if($this->config->environment->type == 'dev') {
            $this->acl = $this->rebuild();
            return $this->acl;
        }

        // Check if the ACL is in APC
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch($this->apcName);
            if (is_object($acl)) {
                $this->acl = $acl;
                return $acl;
            }
        }

        // Check if the ACL is already generated
        if (!file_exists($this->filePath)) {
            $this->acl = $this->rebuild();
            return $this->acl;
        }

        // Get the ACL from the data file
        $data = file_get_contents($this->filePath);
        $this->acl = unserialize($data);

        // Store the ACL in APC
        if (function_exists('apc_store')) {
            apc_store($this->apcName, $this->acl);
        }

        return $this->acl;
    }

    /**
     * Rebuilds the access list into a file or APC
     *
     * @throws \FH\Lib\Exception
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $acl = new AclMemory();
        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        // Add roles
        $roleAdmin = new AclRole(self::ROLE_ADMIN_NAME);
        $roleUser = new AclRole(self::ROLE_USER_NAME);

        $acl->addRole($roleUser);
        $acl->addRole($roleAdmin, $roleUser);

        // Add resources
        foreach ($this->protectedControllers as $role => $entry) {
            foreach ($entry as $controllerName => $actions) {

                $acl->addResource(new AclResource($controllerName), $actions);

                foreach ($actions as $action) {
                    $acl->allow($role, $controllerName, $action);
                }
            }
        }

        if (touch($this->filePath) && is_writable($this->filePath)) {

            file_put_contents($this->filePath, serialize($acl));

            // Store the ACL in APC
            if (function_exists('apc_store')) {
                apc_store($this->apcName, $acl);
            }
        } else {
            throw new Exception('Unable to create the ACL list at ' . $this->filePath);
        }

        return $acl;
    }

    /**
     * Checks if the current user is allowed to access a resource
     * @param \FH\Models\User $user
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed(User $user, $controller, $action)
    {
        if (empty($user->roles)) {
            return false;
        }
        foreach ($user->roles as $role) {
            if ($this->getAcl()->isAllowed($role, $controller, $action)) {
                return true;
            }
        }
        return false;
    }
}
