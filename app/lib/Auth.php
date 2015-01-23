<?php
namespace FH\Lib;
use Phalcon\Mvc\User\Component;
use FH\Models\User;
/**
 * Auth class
 */
class Auth extends Component
{
    /**
     * Session auth type
     * @const AUTH_SESSION
     */
    const AUTH_SESSION = 'session';

    /**
     * Cookie auth type
     * @const AUTH_COOKIE
     */
    const AUTH_COOKIE = 'cookie';

    /**
     * @var \FH\Models\User;
     */
    protected $user = null;

    /**
     * Reset user password
     * @param string $email
     * @throws Exception
     */
    public function resetPassword($email)
    {
        /* @var $user \FH\Models\User */
        $user = User::findFirst([
            'conditions' => " email = :email: AND active = :active:",
            'bind' => ['email' => $email, 'active' => true]
        ]);

        // Check if the user exist
        if (empty($user)) {
            throw new Exception('User with email ' . $email . ' not found');
        }
        $password = $this->di->get('helper')->getToken(6, true, 'lud');
        $user->setPassword($password);
        $user->save();
        $this->di->get('mail')->send($user->email, 'New password: ' . $password);
    }

    /**
     * Create default user
     * @return User|null
     */
    public function defaultUserCreate()
    {
        if (!User::find()->count()) {
            $config = $this->di->get('config');
            $user = new User();
            $user->setData($config->user->toArray());
            $user->roles = [$config->user->role];
            $user->setPassword($config->user->password);
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Check user credentials
     * @param string $email
     * @param string $password
     * @param boolean $remember
     * @return \FH\Models\User
     * @throws \FH\Lib\Exception
     */
    public function check($email, $password, $remember = false)
    {
        //Create default user
        $this->defaultUserCreate();

        /* @var $user \FH\Models\User */
        $user = User::findFirst([
            'conditions' => " email = :email: AND active = :active:",
            'bind' => ['email' => $email, 'active' => true]
        ]);

        // Check if the user exist
        if (empty($user)) {
            throw new Exception('User with email ' . $email . ' not found');
        }

        // Check user password
        if (!password_verify($password, $user->password)) {
            throw new Exception('Wrong password for user with email ' . $email);
        }

        return $this->login($user, $remember);
    }

    /**
     * Login user
     * @param \FH\Models\User $user
     * @param boolean $remember
     * @return \FH\Models\User
     */
    public function login(User $user, $remember = false)
    {
        $this->setSession($user);
        if ($remember) {
            $user = $this->setCookie($user);
        }
        $user->lastLogin = new \DateTime();
        $user->save();

        // Create log
        $this->getDI()->get('logger')->log('Logged user #' . $user->id . ' with email: '. $user->email);

        return $user;
    }

    /**
     * Set auth session
     * @param \FH\Models\User $user
     * @return \FH\Models\User
     */
    private function setSession(User $user)
    {
        $this->session->set('auth', $user->id);

        return $user;
    }

    /**
     * Set auth session
     * @param \FH\Models\User $user
     * @return \FH\Models\User
     */
    private function setCookie(User $user)
    {
        $token = $this->di->get('helper')->getToken(40, false, 'lud');
        $this->cookies->set('auth', serialize(['id' => $user->id, 'token' => $token]), time() + 60 * 60 * 24 * 7);
        $user->cookie = password_hash($token, PASSWORD_DEFAULT);
        $user->cookieIp = $this->request->getClientAddress();

        return $user;
    }
    /**
     * Get auth cookie
     * @return boolean|array
     */
    private function getCookie()
    {
        if ($this->cookies->has('auth')) {
            $data = unserialize($this->cookies->get('auth')->getValue());
            if (!empty($data['id']) && !empty($data['token'])) {
                return $data;
            }
        }
        return false;
    }
    /**
     * Check if user is logged
     * @return boolean|string
     */
    public function isLogged()
    {
        if (!empty($this->session->get('auth'))) {
            return self::AUTH_SESSION;
        }
        if ($this->getCookie()) {
            return self::AUTH_COOKIE;
        }
        return false;
    }
    /**
     * Remove auth session
     */
    public function logout()
    {
        $user = $this->getUser();
        if ($user) {
            $user->cookie = null;
            $user->cookieIp = null;
            $user->save();
        }
        $this->session->remove('auth');
        if ($this->getCookie()) {
            $this->cookies->delete('auth');
        }
    }
    /**
     * Return user instance
     * @return \FH\Models\User|null
     */
    public function getUser()
    {
        $type = $this->isLogged();
        if (!$type) {
            return null;
        }
        if (!empty($this->user)) {
            return $this->user;
        }
        if ($type == self::AUTH_SESSION) {
            /* @var $user \FH\Models\User */
            $user = User::findFirst($this->session->get('auth'));
        }
        if ($type == self::AUTH_COOKIE) {
            $cookie = $this->getCookie();

            /* @var $user \FH\Models\User */
            $user = User::findFirst($cookie['id']);
            $ip = $this->request->getClientAddress();
            if (empty($user) || !password_verify($cookie['token'], $user->cookie) || $user->cookieIp != $ip) {
                return null;
            }
        }
        if (empty($user) || !$user->active) {
            return null;
        }
        $this->user = $user;
        return $user;
    }
}