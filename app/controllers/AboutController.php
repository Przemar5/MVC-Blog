<?php

//namespace App\Controller;
//use Core\Controller;


class AboutController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index_action()
	{
		$this->view->render('about/index');
	}
}