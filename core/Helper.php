<?php


class Helper
{
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function getDate($timeString)
    {
        $timestamp = strtotime($timeString);

        return date('Y, j M, g:i A');
    }
}