<?php

namespace App;

use Ramsey\Uuid\Uuid;

/**
 * Class Helper
 * @package App
 */
class Helper
{
    public static function generateUuid()
    {
        return Uuid::uuid1()->toString();
    }
}