<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

/**
 * Class Helper
 * @package App
 */
class CommonHelper
{
    public static function generateUuid()
    {
        return Uuid::uuid1()->toString();
    }
}