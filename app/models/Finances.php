<?php
namespace FH\Models;

use Phalcon\Mvc\Model\Validator\PresenceOf as PresenceOfValidator;
use Phalcon\Mvc\Model\Validator\Numericality as NumericalityValidator;

/**
 * User model
 */
class Finances extends Base
{

    /**
     * @var float
     */
    public $price;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var boolean
     */
    public $incoming = false;

    /**
     * @return string
     */
    public function getSource()
    {
        return 'finances';
    }

    public function initialize()
    {
        $this->hasMany('id', '\FH\Models\FinancesTag', 'finances_id', ['alias' => 'tags']);
        $this->belongsTo('user_id', '\FH\Models\User', 'id', ['alias' => 'user']);
    }

    /**
     * Entry validation
     * @return boolean
     */
    public function validation()
    {
        //price
        $this->validate(new PresenceOfValidator([
            'field' => 'price',
            'message' => 'The price is required'
        ]));

        //price
        $this->validate(new PresenceOfValidator([
            'field' => 'price',
            'message' => 'The price is required'
        ]));

        $this->validate(new NumericalityValidator([
            'field' => 'price',
            'message' => 'The price is not valid'
        ]));

        return $this->validationHasFailed() != true;
    }
}