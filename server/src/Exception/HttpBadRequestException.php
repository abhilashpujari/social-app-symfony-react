<?php
namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpBadRequestException
 * @package App\Exception
 */
class HttpBadRequestException extends Exception
{
    /**
     * @param array $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        array $message = [],
        $code = Response::HTTP_BAD_REQUEST,
        Exception $previous = null
    ) {
        parent::__construct(json_encode($message), $code, $previous);
    }
}