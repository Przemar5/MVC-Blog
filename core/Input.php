<?php


class Input
{
	private $_data = [], $_rules = [];
	
	
	public static function isGet()
	{
		return self::getRequestMethod() === 'GET';
	}
	
	public static function isPost()
	{
		return self::getRequestMethod() === 'POST';
	}
	
	public static function getRequestMethod()
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}
}