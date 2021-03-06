<?php

namespace FH\Validators;

use Phalcon\Mvc\Model\Validator;
use Phalcon\Mvc\Model\ValidatorInterface;
use Phalcon\Mvc\ModelInterface;

/**
 * Uniqueness validator
 */
class Uniqueness extends Validator implements ValidatorInterface
{
    /**
     * @param \Phalcon\Mvc\ModelInterface $model
     * @return boolean
     */
    public function validate(ModelInterface $model)
    {
        $field = $this->getOption('field');
        $id = $model->readAttribute('id');
        $result = true;

        if (empty($model->readAttribute($field))) {
            return $result;
        }

        $conditions = [
            'conditions' => $field . ' = :' . $field . ':',
            'bind' => [$field => $model->readAttribute($field)]
        ];

        $entries = $model->find($conditions);

        if (empty($id) && count($entries)) {
            $result = false;
        }

        if (!empty($id) && count($entries) && $entries[0]->id != $id) {
            $result = false;
        }

        if (!$result) {
            $message = "The " . $field . " must be unique";

            if (!empty($this->getOption('message'))) {
                $message = $this->getOption('message');
            }
            $this->appendMessage($message, $field, "Unique");
        }

        return $result;
    }
}
