<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpUnauthorizedException
 * @package App\Exception
 */
class HttpUnauthorizedException extends Exception
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_UNAUTHORIZED);
    }
}