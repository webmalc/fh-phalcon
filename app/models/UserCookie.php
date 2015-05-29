<?php
namespace FH\Models;

use \Phalcon\Mvc\Model;

/**
 * UserCookie model
 */
class UserCookie extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $user_id;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    public $ip;

    /**
     * @return string
     */
    public function getSource()
    {
        return 'user_cookies';
    }

    public function initialize()
    {
        $this->belongsTo('user_id', '\FH\Models\User', 'id', ['alias' => 'user']);
    }
}