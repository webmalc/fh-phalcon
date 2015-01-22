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
        $user = Users::findFirst([
            'conditions' => " email = :email: AND active = :active:",
            'bind' => ['email' => $email, 'active' => true]
        ]);

        // Check if the user exist
        if (empty($user)) {
            throw new Exception('User with email ' . $email . ' not found');
        }
        $password = $this->di->helper->getToken(6, true, 'lud');
        $user->setPassword($password);
        $user->save();
        $this->di->mail->send($user->email, 'New password: ' . $password);
    }

    /**
     * Check user credentials
     * @param string $email
     * @param string $password
     * @param boolean $remember
     * @return FH\Frontend\Models\Users
     * @throws \FH\Lib\Exception
     */
    public function check($email, $password, $remember = false)
    {
        $user = Users::findFirst([
            ['email' => $email, 'active' => true]
        ]);
// Check if the user exist
        if (empty($user)) {
            throw new Exception('Не существует пользователя с email: ' . $email);
        }
// Check user passsord
        if (!password_verify($password, $user->password)) {
            throw new Exception('Неверный пароль для пользователя с email: ' . $email);
        }
        return $this->login($user, $remember);
    }
    /**
     * Login user
     * @param \FH\Frontend\Models\Users $user
     * @param boolean $remember
     * @return type
     */
    public function login(Users $user, $remember = false)
    {
        $this->setSession($user);
        if ($remember) {
            $user = $this->setCookie($user);
        }
        $user->lastLogin = new \DateTime();
        $user->save();
// Create log
        $this->getDI()->get('logger')->log('Logged user #' . $user->getId() . ' with email: '. $user->email);
        return $user;
    }
    /**
     * Set auth session
     * @param \FH\Frontend\Models\Users $user
     */
    private function setSession(Users $user)
    {
        $this->session->set('auth', $user->getId());
    }
    /**
     * Set auth session
     * @param \FH\Frontend\Models\Users $user
     * @return \FH\Frontend\Models\Users
     */
    private function setCookie(Users $user)
    {
        $token = $this->helper->getToken(40, false, 'lud');
        $this->cookies->set('auth', serialize(['id' => $user->getId(), 'token' => $token]), time() + 60 * 60 * 24 * 7);
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
        $this->session->remove('auth');
        if ($this->getCookie()) {
            $this->cookies->delete('auth');
        }
    }
    /**
     * Return user instance
     * @return \FH\Frontend\Models\Users|null
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
            $user = Users::findById($this->session->get('auth'));
        }
        if ($type == self::AUTH_COOKIE) {
            $cookie = $this->getCookie();
            $user = Users::findById($cookie['id']);
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