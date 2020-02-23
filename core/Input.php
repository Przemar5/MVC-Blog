<?php


class Input
{
	private $_data = [], $_rules = [];
	
	
	public function isGet()
	{
		return $this->getRequestMethod() === 'GET';
	}
	
	public function isPost()
	{
		return $this->getRequestMethod() === 'POST';
	}
	
	public function getRequestMethod()
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}
}