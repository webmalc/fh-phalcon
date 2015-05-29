<?php
namespace FH\Lib;

use FH\Models\LoginAttempt;
use FH\Models\UserCookie;
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
            throw new Exception('User with email '.$email.' not found');
        }
        $password = $this->di->get('helper')->getToken(6, true, 'lud');
        $user->setPassword($password);
        $user->save();
        $this->di->get('mail')->send($user->email, 'New password: '.$password);
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
     * Add LoginAttempt
     * @return LoginAttempt
     */
    public function addLoginAttempt()
    {
        $attempt = $this->getLoginAttempt();

        if (!empty($attempt)) {
            $attempt->attempt++;
            $attempt->date = new \DateTime();
            $attempt->save();

            return $attempt;
        }
        $attempt = new LoginAttempt();
        $attempt->ip = $this->request->getClientAddress();;
        $attempt->attempt = 1;
        $attempt->date = new \DateTime();
        $attempt->save();

        return $attempt;
    }

    /**
     * Return LoginAttempt entry by IP
     * @param int $max
     * @return LoginAttempt
     */
    public function getLoginAttempt($max = 0)
    {
        $bind = ['ip' => $this->request->getClientAddress(), 'active' => true];
        $attemptSql = '';

        if ($max) {
            $bind['attempt'] = $max;
            $attemptSql = ' AND attempt >= :attempt:';
        }

        return LoginAttempt::findFirst([
            'conditions' => " ip = :ip: AND active = :active:".$attemptSql,
            'bind' => $bind
        ]);
    }

    /**
     * Remove  LoginAttempt entry by IP
     * @return bool
     */
    public function removeLoginAttempt()
    {
        $attempt = $this->getLoginAttempt();

        if (!empty($attempt)) {
            return $attempt->delete();
        }

        return false;
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

        $config = $this->di->get('config');
        $attempt = $this->getLoginAttempt($config->auth->maxLoginAttempts);
        if (!empty($attempt)) {
            $date = clone $attempt->date;
            $date->modify($config->auth->loginAttemptsBlockDuration);

            if (new \DateTime() <= $date) {
                throw new Exception('Access denied for '.$config->auth->loginAttemptsBlockDuration);
            }
            $this->removeLoginAttempt();
        }

        // Check if the user exist
        if (empty($user)) {
            $this->addLoginAttempt();
            throw new Exception('User with email '.$email.' not found');
        }

        // Check user password
        if (!password_verify($password, $user->password)) {
            $this->addLoginAttempt();
            throw new Exception('Wrong password for user with email '.$email);
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
        $this->removeLoginAttempt();

        $this->setSession($user);
        if ($remember) {
            $user = $this->setCookie($user);
        }
        $user->lastLogin = new \DateTime();
        $user->save();

        // Create log
        $this->getDI()->get('logger')->log('Logged user #'.$user->id.' with email: '.$user->email);

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

        //remove old cookies by IP
        foreach ($user->getCookies("ip = '".$this->request->getClientAddress()."'") as $oldCookie) {
            $oldCookie->delete();
        }

        //save cookie to DB
        $cookie = new UserCookie();
        $cookie->user = $user;
        $cookie->hash = password_hash($token, PASSWORD_DEFAULT);
        $cookie->ip = $this->request->getClientAddress();
        $cookie->save();

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
     * @param User $user
     */
    public function logout(User $user = null)
    {
        if (!$user) {
            $user = $this->getUser();
        }
        if ($user) {
            //remove old cookies by IP
            foreach ($user->getCookies("ip = '".$this->request->getClientAddress()."'") as $oldCookie) {
                $oldCookie->delete();
            }
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
            $user = User::findFirst($this->session->get('auth'));
        }
        if ($type == self::AUTH_COOKIE) {
            $cookie = $this->getCookie();
            $ip = $this->request->getClientAddress();
            $cookieEntry = UserCookie::find([
                'conditions' => " user_id = :user_id: AND ip = :ip:",
                'bind' => ['user_id' => $cookie['id'], 'ip' => $ip]
            ]);

            if (empty($cookieEntry) || !password_verify($cookie['token'], $cookieEntry->hash) || $cookieEntry->ip != $ip) {
                if ($user) {
                    $this->logout($user);
                }
                return null;
            }
            $user = $cookieEntry->user;
        }
        if (empty($user) || !$user->active) {
            return null;
        }
        $this->user = $user;

        return $user;
    }
}