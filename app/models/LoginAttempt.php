<?php
namespace FH\Models;

use FH\Validators\Uniqueness as UniquenessValidator;

/**
 * LoginAttempt model
 */
class LoginAttempt extends Base
{
    /**
     * @var int
     */
    public $attempt;

    /**
     * @var string
     */
    public $ip;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @return string
     */
    public function getSource()
    {
        return 'login_attempts';
    }

    /**
     * Entry validation
     * @return boolean
     */
    public function validation()
    {

        $this->validate(new UniquenessValidator([
            'field' => 'ip',
            'message' => 'A attempt with that ip already exists'
        ]));
        return $this->validationHasFailed() != true;
    }
}