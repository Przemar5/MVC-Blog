<?php


class UsersModel extends Model
{
    public $id, $first_name, $last_name, $username,
            $password, $email, $created_at, $updated_at, $deleted, $errors;

    private $loginValidationRules = [
        'username' => [
            'required' => ['msg' => 'Username is required.'],
            'min' => ['args' => [8], 'msg' => 'Username must be at least 8 characters.'],
            'max' => ['args' => [150], 'msg' => 'Username must be less or equal 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z _\-]+'], 'msg' => 'Username contains forbidden characters.'],
        ],
        'password' => [
            'required' => ['msg' => 'Password is required.'],
            'min' => ['args' => [8], 'msg' => 'Password must be at least 8 characters.'],
            'max' => ['args' => [150], 'msg' => 'Password must be less or equal 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z _\-]+'], 'msg' => 'Password contains forbidden characters.'],
        ]
    ];

    public function __construct()
    {
        parent::__construct('users');
    }

    public function check()
    {
        $this->validation = new Validator;

        $this->validation->check([
            'username' => $this->username,
            'password' => $this->password
        ], $this->loginValidationRules);

        if ($this->validation->passed())
        {
            $user = $this->findFirst([
                'conditions' => 'username = ?',
                'bind' => [$this->username]
            ], true);

            if ($user && $user->password === $this->password)
            {
                $this->populate($user);

                return true;
            }
            else
            {
                $this->errors = ['not_found' => 'Invalid username or password.'];
            }
        }
        else
        {
            $this->errors = $this->validation->errors();
        }
    }
	
	public static function currentUserId()
	{
		return Session::get(USER_SESSION_NAME);
	}
}