<?php

namespace App\Helpers;

use voku\helper\AntiXSS;

class TextHelper
{
    public static  function purifyContent($content)
    {
        $antiXss = new AntiXSS();

        return $antiXss->xss_clean($content);
    }
}