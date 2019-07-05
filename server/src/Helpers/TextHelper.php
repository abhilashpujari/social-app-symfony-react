<?php

namespace App\Helpers;

use voku\helper\AntiXSS;

/**
 * Class TextHelper
 * @package App\Helpers
 */
class TextHelper
{
    /**
     * @param $content
     * @return mixed
     */
    public static  function purifyContent($content)
    {
        $antiXss = new AntiXSS();

        return $antiXss->xss_clean($content);
    }
}