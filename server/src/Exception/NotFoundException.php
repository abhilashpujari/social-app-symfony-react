<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NotFoundException
 * @package App\Exception
 */
class NotFoundException extends Exception implements ApiExceptionInterface
{
    /**
     * @param null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}