<?php


class Controller
{
	public function __construct()
	{
		//
	}
	
	public function view()
	{
		$this->view = new View;
		
		return $this->view;
	}
}