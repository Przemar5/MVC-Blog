<?php


class ArrayHelper
{
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
		if (empty($data) || !count($data))
		{
			return false;
		}
		
		foreach ($data as $object)
		{
			if (method_exists(get_class($object), $func))
			{
				call_user_func_array([$object, $func], $args);
			}
		}
		
		return true;
	}
}