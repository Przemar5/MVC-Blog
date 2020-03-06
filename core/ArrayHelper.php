<?php


class ArrayHelper
{
    public static function last($arr)
    {
        return $arr[count($arr) - 1];
    }

    public static function flattenSingles($array = [], $objectMode = false)
    {
        $result = [];

        if (!empty($array))
        {
            foreach ($array as $key => $value)
            {
                if (gettype($value) == 'array')
                {
                    $result[$key] = self::flattenSingles($value);
                }
                else if (gettype($value) == 'object')
                {
                    $result[$key] = self::flattenSingles($value);
                }
                else
                {
                    return $value;
                }
            }
        }

        return $result;
    }
	
	public static function callMethod($data, $func, $args = [])
	{
		$result = [];
		
		if (empty($data) || !count($data))
		{
			return false;
		}
		
		foreach ($data as $object)
		{
			if (method_exists(get_class($object), $func))
			{
				$result[] = call_user_func_array([$object, $func], $args);
			}
		}
		
		return $result;
	}
	
	public static function callForArgs($object, $func, $args = [])
	{
		$result = [];
		
		if (empty($args) || !count($args) || !method_exists(get_class($object), $func))
		{
			return false;
		}
		
		foreach ($args as $arg)
		{
			$result[] = call_user_func_array([$object, $func], [$arg]);
		}
		
		return $result;
	}
	
	public static function ifChildArray($array)
	{
		if (!is_array($array))
		{
			return false;
		}
		
		foreach ($array as $element)
		{
			if (is_array($element))
			{
				return true;
			}
		}
		
		return false;
	}
	
	public static function getGrandChildArray(&$array)
	{
		if (!is_array($array))
		{
			return false;
		}
		
		foreach ($array as $element)
		{
			if (is_array($element) && !empty($element))
			{
				foreach ($element as $e)
				{
					if (is_array($e))
					{
						return $e;
					}
				}
			}
		}
		
		return false;
	}
}