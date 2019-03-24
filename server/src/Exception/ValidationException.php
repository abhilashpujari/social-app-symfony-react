<?php
namespace App\Exception;

use Exception;

/**
 * Class ValidationException
 * @package App\Exception
 */
class ValidationException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 400, Exception $previous = null)
    {
        if (is_array($message)) {
            $stringMessage = implode("\n", $message);
        } else {
            $stringMessage = $message;
        }

        parent::__construct($stringMessage, $code, $previous);
    }
}