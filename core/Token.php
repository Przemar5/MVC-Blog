<?php


class Token
{
    public static function generate()
    {
        return Helper::generateRandomString(24);
    }
}