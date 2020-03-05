<?php


class UserAgent
{
	public static function agent()
	{
		$uAgent = $_SERVER['HTTP_USER_AGENT'];
		
		return preg_replace('/(\/[\d\.\/]*)|(\([^\)]*\))/', '', $uAgent);
	}
	
	public static function getIp()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		else 
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}
}