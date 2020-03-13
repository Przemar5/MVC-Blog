<?php


class URL
{
	public static function actualUrl()
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	public static function actualUrlWithoutGet()
	{
	    return preg_replace('/\?[^\/]*$/', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
	
	public static function splitUrl($get)
	{
		$url = 'http://';
		$url .= $_SERVER['HTTP_HOST'];    
    	$url .= $_SERVER['REQUEST_URI'];
		$url = preg_split('/\?' . $get . '=([^&])*(?=\&)?/i', $url);
		
		return $url;
	}

    public static function insertGet($param, $value)
    {
        if (isset($_GET[$param]))
        {
            return self::updateGet($param, $value);
        }
        return (preg_match('/\?[^\/]*$/', self::actualUrl()))
                ? self::actualUrl() . '&' . $param . '=' . $value
                : self::actualUrl() . '?' . $param . '=' . $value;
    }

	public static function updateGet($param, $value)
	{
	    return preg_replace('/(?!(\?|\&))' . $param . '=[^\&]*[^\/]*/', $param . '=' . $value, self::actualUrl());
	}
}