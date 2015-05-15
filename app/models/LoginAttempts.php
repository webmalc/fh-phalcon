<?php
namespace FH\Models;

use FH\Validators\Uniqueness as UniquenessValidator;

/**
 * LoginAttempts model
 */
class LoginAttempts extends Base
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