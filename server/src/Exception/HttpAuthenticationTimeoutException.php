<?php
namespace App\Exception;

use Exception;

/**
 * Class HttpAuthenticationTimeoutException
 * @package App\Exception
 */
class HttpAuthenticationTimeoutException extends Exception
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, 419);
    }
}