<?php


class URL
{
	public static function actualUrl()
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	
	public static function splitUrl($get)
	{
		$url = 'http://';
		$url .= $_SERVER['HTTP_HOST'];    
    	$url .= $_SERVER['REQUEST_URI'];
		$url = preg_split('/\?' . $get . '=([^&])*(?=\&)?/i', $url);
		
		return $url;
	}
}