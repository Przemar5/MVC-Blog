<?php


class Router
{
    public static function route($url)
    {

    }

    public static function redirect($location)
    {
        if (!headers_sent())
        {
            header('Location: ' . URL . $location);
            exit;
        }
        else
        {
            echo '<script type="text/javascript">';
            echo 'window.location.href="', URL, $location, '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=', $location, '"/>';
            echo '</noscript>';
            exit;
        }
    }

    public static function checkNoscript()
    {
        echo '<script type="text/javascript">';
        echo 'window.location.href="', URL::insertGet('noscript', 0), '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=', URL::insertGet('noscript', 1), '"/>';
        echo '</noscript>';
        exit;
    }
}