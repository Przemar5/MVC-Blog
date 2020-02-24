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
		$input = new Input;
		
		if ($input->isPost())
		{
            $this->loadModel('users');
            $this->usersModel->populate($_POST);
            d($_POST);die;

            if ($this->usersModel->check())
            {
                session_regenerate_id();

                $value = uniqid() . Helper::generateRandomString(16);
                Session::set(USER_SESSION_NAME, $value);

                $path = URL . 'dashboard';
                header('Location: ' . $path);
            }
            else
            {
                Session::setMultiple($this->usersModel->errors);

                $path = URL . 'login';
                header('Location: ' . $path);
            }
		}
	}
}