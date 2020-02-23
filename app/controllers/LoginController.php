<?php


class LoginController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index_action()
	{
		$this->view()->render('login/index');
	}
	
	public function verify_action()
	{
		$input = new Input;
		
		if ($input->isPost())
		{
			$validation = new Validator;
			
			$validation->check($_POST, [
				'username' => [
					'required' => ['msg' => 'Username is required.']
				],
				'password' => true
			]);
		}
	}
}