<?php
namespace App\Service;

use App\Exception\ApiException;
use App\Exception\ValidationException;

class Validator
{
    /**
     * An array of validators
     * @var array
     */
    protected $validators = [];

    /**
     * @param $validator
     * @param $key
     * @param $message
     * @param bool|true $isRequired
     * @return $this
     */
    public function setValidator($validator, $key, $message, $isRequired = true)
    {
        $validation = new \stdClass;
        $validation->validator = $validator;
        $validation->key = $key;
        $validation->message = $message;
        $validation->isRequired = $isRequired;
        $this->validators[] = $validation;
        return $this;
    }

    public function validate($data)
    {
        foreach ($this->validators as $validation) {
            if (!isset($data->{$validation->key}) && $validation->isRequired) {
                throw new ApiException("Required parameter '$validation->key' not found.", 409);
            } elseif (!isset($data->{$validation->key}) && !$validation->isRequired) {
                return true;
            } elseif ($validation->validator->validate($data->{$validation->key}) === false) {
                throw new ValidationException($validation->message);
            }
        }

        return  true;
    }
}