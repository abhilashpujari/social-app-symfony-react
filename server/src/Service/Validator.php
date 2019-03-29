<?php
namespace App\Service;

use App\Exception\HttpBadRequestException;
use App\Exception\HttpConflictException;
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
    public function setValidator($validator, $key, $message, $isRequired = false)
    {
        $validation = new \stdClass;
        $validation->validator = $validator;
        $validation->key = $key;
        $validation->message = $message;
        $validation->isRequired = $isRequired;
        $this->validators[] = $validation;
        return $this;
    }

    /**
     * @param $data
     * @return bool
     * @throws HttpBadRequestException
     * @throws HttpConflictException
     * @throws ValidationException
     */
    public function validate($data)
    {
        if (!is_object($data)) {
            throw new HttpConflictException('Data for validation must be an object');
        }
        
        foreach ($this->validators as $validation) {
            if (!isset($data->{$validation->key}) && $validation->isRequired) {
                throw new HttpBadRequestException("Required parameter '$validation->key' not found.");
            } elseif (!isset($data->{$validation->key}) && !$validation->isRequired) {
                return true;
            } elseif ($validation->validator->validate($data->{$validation->key}) === false) {
                throw new ValidationException($validation->message);
            }
        }

        return  true;
    }
}