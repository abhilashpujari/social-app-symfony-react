<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpNotFoundException
 * @package App\Exception
 */
class HttpNotFoundException extends Exception
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}