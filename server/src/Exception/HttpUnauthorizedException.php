<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpUnauthorizedException
 * @package App\Exception
 */
class HttpUnauthorizedException extends Exception implements ApiExceptionInterface
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_UNAUTHORIZED);
    }
}