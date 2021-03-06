<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpConflictException
 * @package App\Exception
 */
class HttpConflictException extends Exception
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_CONFLICT);
    }
}