<?php

namespace Resources\Render;

class Json
{
    public static function response($content = "")
    {
        echo self::render();
    }

    public static function render($content = "")
    {
        $response = json_encode($content, JSON_UNESCAPED_UNICODE);
        return $response;
    }
}