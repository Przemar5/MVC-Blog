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
	
	public static function selectProperty($data, $property)
	{
		dd($data);
		echo 'good';die;
	}
}