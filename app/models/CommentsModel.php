<?php


class CommentsModel extends Model
{
    public $id, $username, $email, $message, $user_id, $created_at, $updated_at, $deleted, $post_id, $parent_id, $children_ids;
    protected $formValues = ['id', 'username', 'email', 'message', 'post_id'];

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
			'children_ids' => 'parents_comments',
		],
		'select' => [
			'parents_comments' => ['id'],
		],
		'insert' => [
			'posts_comments' => [
				'insert' => [
					'post_id' => 'post_id',
					'comment_id' => 'id',
				],
			],
			'parents_comments' => [
			    'insert' => [
			        'parent_id' => 'parent_id',
			        'comment_id' => 'id'
			    ]
			]
		],
		'delete' => [
		    'posts_comments' => [
		        'delete' => [
		            'comment_id' => 'id'
		        ]
		    ],
		    'parents_comments' => [
		        'delete' => [
		            'comment_id' => 'id'
		        ]
		    ]
		]
	];

    public function __construct()
    {
        parent::__construct('comments');
    }

    public function check($update = false)
    {
        $this->validation = new Validator;

		if ($update)
		{
			$this->ValidationRulesForUpdate();
		}
		else
		{
			$this->ValidationRulesForInsert();
		}
		
		if (empty($this->id) && $user = UsersModel::getLoggedInUser())
		{
			$this->username = $user->username;
			$this->email = $user->email;
			$this->user_id = $user->id;
		}
		$this->parent_id = $this->parent_id ?? 0;

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
	}
	
	private function validationRulesForUpdate()
	{
		$this->validationRules['user_id']['match'] = ['args' => [$this->user_id], 'msg' =>''];
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
			$data = [
                'username' => $this->username,
                'email' => $this->email,
                'message' => $this->message,
                'user_id' => $this->user_id,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            return $this->update($this->id, $data);
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

			return $this->insert($data, true);
		}
	}

	public function getAdditionalInfo()
    {
        if (empty($this->id))
        {
            return false;
        }

        if (empty($this->dependencies['binding']))
        {
            return false;
        }

        foreach ($this->dependencies['binding'] as $property => $object)
        {
            if (empty($this->{$property}))
            {
            /*
                $this->{$property} = ModelMediator::make($this->_table . $object,
                                                         $property . 'ForPost',
                                                         [$this->id,
                                                          ['values' => $this->dependencies['select'][$object]]]);
                                                          */
            }
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

	public function findForParent($postId, $parentId = null, $values = [])
	{


        if (empty($postId) || !$post = ModelMediator::make('postsModel', 'findById', [$postId]))
        {
            return false;
        }

        $valuesString = (is_array($values)) ? implode(', ', $values) : $values;
        $valuesString = (!empty($valuesString)) ? $valuesString : '*';
        $conditionString = 'parent_id ';
        $conditionString .= (!empty($parentId)) ? '= ' . $parentId : 'IS NULL';

        $sql = 'SELECT ' . $valuesString . ' FROM ' . $this->_table . ' WHERE id IN ' .
                '(SELECT comment_id FROM parents_comments WHERE comment_id IN ' .
                '(SELECT comment_id FROM posts_comments WHERE ' . $conditionString . '))';

        $result = $this->_db->query($sql)->results();

        dd($result);
	}
/*
	public function commentsForPostDesc($limit, $post)
	{
	    $params['order'] = 'id DESC';
    	$params['limit'] = $limit;

        dd(func_get_args());
	}
*/
	public function lastFromFor($limit, $offset, $params = [])
	{
		foreach ($params['data'] as $table => $column)
		{
			$path[$table] = GraphHelper::findPath(ModelMediator::$refs, $table, $this->_table, key($column));
		}

		$params['order'] = 'id DESC';
		$params['limit'] = $limit;
		$params['from'] = $offset;

		return $this->complexFind($path, $params);
	}

	public function rootIdsForPostDesc($postId)
	{
        $sql = 'SELECT comments.id
                FROM comments, parents_comments
                WHERE comments.id
                IN (SELECT comment_id
                    FROM posts_comments
                    WHERE post_id = ?)
                AND comments.id
                NOT IN (SELECT comment_id
                    FROM parents_comments
                    WHERE comment_id
                    IN (SELECT comment_id
                        FROM posts_comments
                        WHERE post_id = ?))
                ORDER BY comments.id DESC';

        return ArrayHelper::flattenSingles($this->query($sql, [$postId, $postId])->results());
	}

	public function findByIds($ids, $values = ['*'])
	{
	    if (empty($ids))
	    {
	        return false;
	    }

        $valuesString = (is_array($values)) ? implode(', ', $values) : $values;
        $closure = function($v) {   return 'id = ?';    };
        $idsString = (is_array($values)) ? implode(' OR ', array_map($closure, $ids)) : $ids;
	    $sql = 'SELECT ' . $valuesString . ' FROM ' . $this->_table . ' WHERE ' . $idsString;

        return $this->_db->query($sql, $ids)->results();
	}

	public function findLastFromByIds($limit, $offset, $params = [], $postId, $parentId = '')
	{
        $sql = 'SELECT comments.*
                FROM comments, parents_comments
                WHERE comments.id
                IN (SELECT comment_id
                    FROM posts_comments
                    WHERE post_id = ?)
                AND comments.id
                NOT IN (SELECT comment_id
                    FROM parents_comments
                    WHERE comment_id
                    IN (SELECT comment_id
                        FROM posts_comments
                        WHERE post_id = ?))
                ORDER BY comments.id DESC';

        return $this->_db->query($sql, [$postId, $postId], get_class($this))->results();
	}

	public function idDescFor($params)
	{
		foreach ($params['data'] as $table => $column)
		{
			$path[$table] = GraphHelper::findPath(ModelMediator::$refs, $table, $this->_table, key($column));
		}

        $params['values'] = 'id';
		$params['order'] = 'id DESC';

		return ArrayHelper::flattenSingles($this->complexFind($path, $params, false));
	}

    public function idDescForPost($postId)
    {
        $sql = 'SELECT id FROM comments WHERE id IN (SELECT comment_id FROM posts_comments WHERE post_id = ?) ORDER BY id DESC';

        return ArrayHelper::flattenSingles($this->_db->query($sql, [$postId])->results());
    }

    public function lastForPost($limit, $postId)
    {
        $sql = 'SELECT * FROM comments WHERE id IN (SELECT comment_id FROM posts_comments WHERE post_id = ?) ORDER BY id DESC LIMIT ?';

        return $this->_db->query($sql, [$postId, $limit], get_class($this))->results();
    }

    public function lastFromForPost($limit, $offset, $postId)
    {
        $sql = 'SELECT * FROM comments WHERE id IN (SELECT comment_id FROM posts_comments WHERE post_id = ?) AND id < ? ORDER BY id DESC LIMIT ?';

        return $this->_db->query($sql, [$postId, $offset, $limit], get_class($this))->results();
    }
}