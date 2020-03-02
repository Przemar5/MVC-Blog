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

        return date('Y, j M, g:i A', $timestamp);
    }

    public static function currentTimestamp()
    {
        $timestamp = strtotime(time());

        return date('Y-m-d H:i:s', $timestamp);
    }

    public static function linkAssociative($keys, $data)
    {
        $result = [];

        if (count($keys) && count($data))
        {
            foreach ($keys as $key)
            {
                if (array_key_exists($key, $data))
                {
                    $result[$key] = $data[$key];
                }
                else
                {
                    $result[$key] = '';
                }
            }
        }

        return $result;
    }
	
	public static function repeatString($pattern, $number = 1, $glue = '')
	{
		$result = '';
		
		if (!empty($number) && is_integer($number))
		{
			while ($number-- > 0)
			{
				$result .= $pattern;
				
				if ($number >= 1)
				{
					$result .= $glue;
				}
			}
			
			return $result;
		}
	}
	
	public static function tableToModelName($table)
	{
		$table = explode('_', $table);

		$result = array_shift($table);

		if (!empty($table))
		{
			foreach ($table as $part)
			{
				$result .= ucfirst($part);
			}
		}

		return $result;
	}
	
	public static function actualUrl()
	{
		$string = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		return substr($string, 0, strpos($string, '?'));
	}
}