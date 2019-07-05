<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

/**
 * Class Helper
 * @package App
 */
class CommonHelper
{
    /**
     * @return string
     */
    public static function generateUuid()
    {
        return Uuid::uuid1()->toString();
    }
}