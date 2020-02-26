<?php


class Form
{
    public static function posted($values)
    {
        if (gettype($values) === 'array' && count($values))
        {
            echo 'good<br>';
//            foreach ($values as $value)
        }
    }
}