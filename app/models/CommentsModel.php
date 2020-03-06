<?php


class CommentsModel extends Model
{
    public $id, $username, $email, $message, $user_id, $created_at, $updated_at, $deleted, $post_id, $parent_comment_id;
    protected $formValues = ['username', 'email', 'message', 'post_id'];

    private $validationRules = [
        'username' => [
            'required' => ['msg' => 'Username is required.'],
            'min' => ['args' => [6], 'msg' => 'Username must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Username cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Username contains illegal characters.'],
		],
        'email' => [
            'required' => ['msg' => 'Email address is required.'],
            'min' => ['args' => [6], 'msg' => 'Email address must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Email address cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-\.\,]+'], 'msg' => 'Email address contains illegal characters.'],
		],
        'message' => [
            'required' => ['msg' => 'Comment body is required.'],
            'min' => ['args' => [6], 'msg' => 'Email address must be equal or longer than 6 characters.'],
        ],
		'post_id' => [
			'required' => ['msg' => 'req'],
			'numeric' => ['msg' => 'num'],
			'exists' => ['args' => ['posts', 'id'], 'msg' => 'not'],
		],
//		'user_id' => [
//			'required' => ['msg' => ''],
//			'numeric' => ['msg' => ''],
//			'exists' => ['args' => ['users', 'id'], 'msg' => ''],
//		]
    ];
	
	private $dependencies = [
		'binding' => [
			'category' => 'categories',
			'tags' => 'tags'
		],
		'select' => [
			'categories' => ['*'],
			'tags' => ['*']
		],
		'insert' => [
			'posts_comments' => [
				'insert' => [
					'post_id' => 'post_id',
					'comment_id' => 'id',
				],
			],
		],
	];

    public function __construct()
    {
        parent::__construct('comments');
    }

    public function check($update = false)
    {
        $this->validation = new Validator;
		
//		if ($update)
//		{
//			$this->ValidationRulesForUpdate();
//		}
//		else 
//		{
//			$this->ValidationRulesForInsert();
//		}
		
		if ($user = UsersModel::getLoggedInUser())
		{
			$this->username = $user->username;
			$this->email = $user->email;
			$this->user_id = $user->id;
		}
		
        $this->validation->check([
            'username' => $this->username,
            'email' => $this->email,
            'message' => $this->message,
            'post_id' => $this->post_id,
        ], $this->validationRules);

        if ($this->validation->passed())
        {
            return true;
        }
        else
        {
            $this->_errors = $this->validation->errors();

            return false;
        }
    }
	
	private function validationRulesForInsert()
	{
		if (isset($this->validationRules['user_id']['match']))
		{
			unset($this->validationRules['user_id']['match']);
		}
		$this->removeUniqueException('title');
		$this->removeUniqueException('label');
		$this->removeUniqueException('slug');
	}
	
	private function validationRulesForUpdate()
	{
		$this->validationRules['user_id']['match'] = ['args' => [$this->user_id], 'msg' =>''];
		$this->addUniqueException('title');
		$this->addUniqueException('label');
		$this->addUniqueException('slug');
	}
	
	private function addUniqueException($column)
	{
		if (!isset($this->validationRules[$column]['unique']['args'][2]))
		{
			array_push($this->validationRules[$column]['unique']['args'], $this->id);
		}
	}
	
	private function removeUniqueException($column)
	{
		if (isset($this->validationRules[$column]['unique']['args'][2]))
		{
			unset($this->validationRules[$column]['unique']['args'][2]);
		}
	}
	
	public function save()
	{
		if ($this->id)
		{
			die('IS ID');
		}
		else 
		{
			$this->parent_id = null;

			$data = [
				'username' => $this->username,
				'email' => $this->email,
				'message' => $this->message,
				'user_id' => $this->user_id,
				'created_at' => date('Y-m-d H:i:s'),
			];

			if (!$this->insert($data, true))
			{
				return false;
			}

			return true;
		}
	}
	
	public function actOnDependencies($mode)
	{
		if (empty($this->dependencies[$mode]))
		{
			return true;
		}
		
		foreach ($this->dependencies[$mode] as $table => $methods)
		{
			$model = Helper::tableToModelName($table);
			
			foreach ($methods as $method => $values)
			{
				$params = [];
				
				foreach ($values as $key => $value)
				{
					$params[$key] = $this->{$value};
				}
				
	 			if (!ModelMediator::make($model, $method . 'By', [$params]))
	 			{
	 				return false;
	 			}
			}
		}

		return true;
	}
}