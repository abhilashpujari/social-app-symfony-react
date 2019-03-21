<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpAuthenticationTimeoutException
 * @package App\Exception
 */
class HttpAuthenticationTimeoutException extends Exception implements ApiExceptionInterface
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, 419);
    }
}