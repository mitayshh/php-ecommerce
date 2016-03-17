<?php
class HttpHelper
{
    public static function redirect($url)
    {
        header("Location: " . $url);
    }
}
?>