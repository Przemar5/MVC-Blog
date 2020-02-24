<?php


class Session
{
    public static function exists($name)
    {
        return isset($_SESSION[$name]);
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function pop($name)
    {
        $tmp = $_SESSION[$name];
        unset($_SESSION[$name]);

        return $tmp;
    }

    public static function setMultiple($data = [])
    {
        if (!empty($data))
        {
            foreach ($data as $key => $value)
            {
                self::set($key, $value);
            }
        }
    }

    public static function getMultiple($names = [])
    {
        $result = [];

        if (!empty($names))
        {
            foreach ($names as $name)
            {
                if (self::exists($name))
                {
                    $result[$name] = self::get($name);
                }
            }
        }

        return $result;
    }

    public static function popMultiple($names)
    {
        $result = [];

        if (!empty($names))
        {
            foreach ($names as $name)
            {
                if (self::exists($name))
                {
                    $result[$name] = self::pop($name);
                }
            }
        }

        return $result;
    }
}