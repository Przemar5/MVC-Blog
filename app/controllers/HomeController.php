<?php


class HomeController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index_action()
	{
		$this->view->render('home/index');
	}
}