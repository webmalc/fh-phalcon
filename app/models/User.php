<?php
namespace FH\Models;

use FH\Validators\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\PresenceOf as PresenceOfValidator;
use Phalcon\Mvc\Model\Validator\StringLength as StringLengthValidator;

/**
 * User model
 */
class User extends Base
{
    /**
     * @var string
     */
    public $name;

    /**
     * User roles
     * @var array
     */
    public $roles = [];

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var \DateTime
     */
    public $lastLogin;

    /**
     * Not encoded password
     * @var string
     */
    private $plainPassword = null;

    /**
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    public function initialize()
    {
        $this->hasMany('id', '\FH\Models\UserCookie', 'user_id', ['alias' => 'cookies']);
    }

    /**
     * Return username
     * @return string
     */
    public function getUsername()
    {
        return (!empty($this->name)) ? $this->name : $this->email;
    }

    /**
     * Sets new password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->plainPassword = $this->password = $password;

        return $this;
    }

    /**
     * Before save action
     */
    public function beforeSave()
    {
        // encode password
        if (!empty($this->plainPassword)) {
            $this->password = password_hash($this->plainPassword, PASSWORD_DEFAULT);
            $this->plainPassword = null;
        }

        // Set user role
        if (empty($this->roles)) {
            $this->roles = 'user';
        }

        parent::beforeSave();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        unset($data['password']);
        unset($data['cookie']);
        unset($data['cookieIp']);
        $data['username'] = $this->getUsername();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getExcludeFields()
    {
        return array_merge(parent::getExcludeFields(), [
            'password',
            'lastLogin',
            'cookie',
            'cookieIp',
            'roles'
        ]);
    }

    /**
     * Entry validation
     * @return boolean
     */
    public function validation()
    {
        //name
        $this->validate(new StringLengthValidator([
            'field' => 'name',
            'max' => 100,
            'messageMaximum' => 'Name is too long. Maximum 6 characters',
        ]));

        //email
        $this->validate(new PresenceOfValidator([
            'field' => 'email',
            'message' => 'The e-mail is required'
        ]));
        
        $this->validate(new EmailValidator([
            'field' => 'email',
            'message' => 'The e-mail is not valid'
        ]));

        $this->validate(new UniquenessValidator([
            'field' => 'email',
            'message' => 'A user with that email already exists'
        ]));

        //password
        $this->validate(new StringLengthValidator([
            'field' => 'password',
            'min' => 6,
            'messageMinimum' => 'Password is too short. Minimum 6 characters',
        ]));
        return $this->validationHasFailed() != true;
    }

    /**
     * Check user role
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }
}