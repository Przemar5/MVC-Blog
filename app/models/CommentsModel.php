<?php


class CommentsModel extends Model
{
    public $id, $username, $email, $message, $user_id, $created_at, $updated_at, $deleted, $post_id, $parent_id, $children_ids, $subcomments, $subcomments_count;
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

	public function getAdditionalInfo()
    {
        if (empty($this->id))
        {
            return false;
        }

        if (empty($this->dependencies['select']))
        {
            return false;
        }

        foreach ($this->dependencies['select'] as $table => $properties)
        {
            die('ok');
            /*
            if (empty($this->{$property}))
            {
                d('TABLE');
                d($this->_table . $object);
                d('Property');
                d($property . 'ForComment');
                $this->{$property} = ModelMediator::make($this->_table . $object,
                                                         $property . 'ForComment',
                                                         [$this->id,
                                                          ['values' => $this->dependencies['select'][$object]]]);

            }
            */
        }
    }
	
	private $dependencies = [
		/*'binding' => [
			'children_ids' => 'parents_comments',
			'parent_id' => 'parents_comments',
			'post_id' => 'posts_comments'
		],*/
		'select' => [
			'parents_comments' => [
			    'parent_id' => 'parent_id'
			],
			'posts_comments' => [
			    'post_id' => 'post_id'
			]
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
			$this->parent_id = 0;

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

	public function getCommentsTree($postId, $params = [], $class = true)
	{
	    //dd(func_get_args());

	    $rootComments = $this->findForParent($postId, 0, $params, false, $additionalActions = []);

        foreach ($rootComments as $comment)
        {
            $comment->subcomments = $this->getCommentBranch($comment->id, $params, false, $additionalActions);

	        if (!empty($additionalActions))
	        {
	            foreach ($additionalActions as $action => $args)
	            {
	                call_user_func_array([$this, $action], [$args]);
	            }
	        }
        }

	    return $rootComments;
	}

	public function getCommentBranch($commentId, $params = [], $class = true, $additionalActions = [])
	{
	    $subcomments = $this->findSubcomments($commentId, $params, $class);

	    foreach ($subcomments as $subcomment)
	    {
	        $subcomment->subcomments = $this->getCommentBranch($subcomment->id, $params, $class);

	        if (!empty($additionalActions))
	        {
	            foreach ($additionalActions as $action => $args)
	            {
	                call_user_func_array([$this, $action], [$args]);
	            }
	        }
	    }

	    return $subcomments;
	}

	public function findSubcomments($parentId, $params = [], $class = true)
	{
        if (empty($parentId) || !$parent = $this->findById($parentId, ['values' => 'id'], false))
        {
            return false;
        }

        $valuesString = (!empty($params['values']) && is_array($params['values'])) ? implode(', ', $params['values']) : $params['values'];
        $valuesString = (!empty($valuesString)) ? $valuesString : '*';
        $order = (!empty($params['order'])) ? ' ORDER BY ' . $params['order'] : '';
        $limit = (!empty($params['limit'])) ? ' LIMIT ' . $params['limit'] : '';
        $offset = (!empty($params['offset'])) ? ' OFFSET ' . $params['offset'] : '';
        $parentIdString = 'parent_id = ?';
        $params = [$parentId];
        $class = ($class) ? get_class($this) : false;

        $sql = 'SELECT ' . $valuesString . ' FROM ' . $this->_table . ' WHERE id IN ' .
                '(SELECT comment_id FROM parents_comments WHERE ' . $parentIdString . ') ' .
                $order . $limit . $offset;

        return $this->_db->query($sql, $params, $class)->results();
	}

    public function findForPost($postId, $params = [], $class = true)
    {
        if (empty($postId) || !$post = ModelMediator::make('posts', 'findById', [$postId, ['values' => 'id'], false]))
        {
            return false;
        }

        $valuesString = (!empty($params['values']) && is_array($params['values'])) ? implode(', ', $params['values']) : $params['values'];
        $valuesString = (!empty($valuesString)) ? $valuesString : '*';
        $order = (!empty($params['order'])) ? ' ORDER BY ' . $params['order'] : '';
        $limit = (!empty($params['limit'])) ? ' LIMIT ' . $params['limit'] : '';
        $offset = (!empty($params['offset'])) ? ' OFFSET ' . $params['offset'] : '';
        $postIdString = 'post_id = ?';
        $params = [$postId];
        $class = ($class) ? get_class($this) : false;

        $sql = 'SELECT ' . $valuesString . ' FROM ' . $this->_table . ' WHERE id IN ' .
                '(SELECT comment_id FROM posts_comments WHERE ' . $postIdString . ') ' .
                $order . $limit . $offset;

        return $this->_db->query($sql, $params, $class)->results();
    }

	public function findForParent($postId, $parentId = null, $params = [], $class = true, $additionalInfo = true)
	{
        if (empty($postId) || !$post = ModelMediator::make('posts', 'findById', [$postId, ['values' => 'id'], false]))
        {
            return false;
        }

        $valuesString = (!empty($params['values']) && is_array($params['values'])) ? implode(', ', $params['values']) : $params['values'];
        $valuesString = (!empty($valuesString)) ? $valuesString : '*';
        $order = (!empty($params['order'])) ? ' ORDER BY ' . $params['order'] : '';
        $limit = (!empty($params['limit'])) ? ' LIMIT ' . $params['limit'] : '';
        $offset = (!empty($params['offset'])) ? ' OFFSET ' . $params['offset'] : '';
        $postIdString = 'post_id = ?';
        $parentIdString = (!empty($parentId)) ? 'parent_id = ' . $parentId : '(parent_id IS NULL OR parent_id = 0)';
        $bind = [$postId];
        $class = ($class) ? get_class($this) : false;

        if (!empty($parentId))
        {
            $params = array_unshift($params, $parentId);
        }

        $sql = 'SELECT ' . $valuesString . ' FROM ' . $this->_table . ' WHERE id IN ' .
                '(SELECT comment_id FROM parents_comments WHERE ' . $parentIdString . ' AND comment_id IN ' .
                '(SELECT comment_id FROM posts_comments WHERE ' . $postIdString . ')) ' .
                $order . $limit . $offset;

        $results = $this->_db->query($sql, $bind, $class)->results();

        if ($additionalInfo && !empty($results) && count($results))
        {
            foreach ($results as $result)
            {
                $result->post_id = $postId;
                $result->parent_id = $parentId;
                $result->subcomments_count = ModelMediator::make('parentsComments', 'countChildrenFor', [$result->id]);
            }
        }

        return $results;
	}

	public function findFor($params = [])
    {
        foreach ($params['data'] as $table => $column)
        {
            $path[$table] = GraphHelper::findPath(ModelMediator::$refs, $table, $this->_table, key($column));
        }

        return $this->complexFind2($path, $params);
    }

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

	public function findByIds($ids, $params = [], $class = true)
	{
	    if (empty($ids))
	    {
	        return false;
	    }

        if (is_array($ids))
        {
            $lambda = function($v) {   return 'id = ?';    };
            $idsString = '(' . implode(' OR ', array_map($lambda, $ids)) . ')';
            $params['bind'] = $params['bind'] ?? [];
            $params['bind'] = array_merge($params['bind'], array_reverse($ids));
            $params['conditions'] = (!empty($params['conditions'])) ? '(' . $params['conditions'] . ') AND ' : '';
            $params['conditions'] .= $idsString;
        }
        else if (is_string($ids) || is_int($ids))
        {
            $params['bind'][] = $ids;
            $params['conditions'] = (!empty($params['conditions'])) ? '(' . $params['conditions'] . ') AND id = ?' : '';
        }
        else
        {
            return false;
        }

        return $this->find($params, $class);
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