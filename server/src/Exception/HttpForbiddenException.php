<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpForbiddenException
 * @package App\Exception
 */
class HttpForbiddenException extends Exception
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_FORBIDDEN);
    }
}