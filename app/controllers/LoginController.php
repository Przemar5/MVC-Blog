<?php


class LoginController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index_action()
	{
        $this->view->errors = Session::popMultiple(['username', 'password', 'not_found']);
		$this->view->render('login/index');
	}
	
	public function verify_action()
	{
		if (Input::isPost())
		{
            $this->loadModel('users');
            $this->usersModel->populate($_POST);

            if ($this->usersModel->check())
            {
                session_regenerate_id();

//                $value = uniqid() . Helper::generateRandomString(16);
                Session::set(USER_SESSION_NAME, $this->usersModel->id);
                Router::redirect('dashboard');
            }
            else
            {
                Session::setMultiple($this->usersModel->errors);
                Router::redirect('login');
            }
		}
	}
}