<?php


class PostsModel extends Model
{
    public $id, $label, $title, $slug, $category_id, $category = null, $tag_ids, $tags = [],
			$tagsString = '', $body, $user_id, $created_at, $updated_at, $deleted;
    protected $formValues = ['title', 'label', 'slug', 'category_id', 'tag_ids', 'body', 'user_id'];

    private $validationRules = [
        'title' => [
            'required' => ['msg' => 'Post title is required.'],
            'min' => ['args' => [6], 'msg' => 'Post title must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Post title cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Post title contains illegal characters.'],
        	'unique' => ['args' => ['posts', 'title'], 'msg' => 'Post title must be unique'],
		],
        'label' => [
            'required' => ['msg' => 'Post label is required.'],
            'min' => ['args' => [6], 'msg' => 'Post label must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Post label cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Post label contains illegal characters.'],
        	'unique' => ['args' => ['posts', 'label'], 'msg' => 'Post label must be unique'],
		],
        'slug' => [
            'required' => ['msg' => 'Slug is required.'],
            'min' => ['args' => [6], 'msg' => 'Slug must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Slug cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+'], 'msg' => 'Slug contains illegal characters.'],
        	'unique' => ['args' => ['posts', 'slug'], 'msg' => 'Post slug must be unique'],
		],
		'category_id' => [
            'required' => ['msg' => ''],
            'numeric' => ['msg' => ''],
            'regex' => ['args' => ['[0-9]{1,7}'], 'msg' => ''],
            'exists' => ['args' => ['categories', 'id'], 'msg' => '']
        ],
		'tag_ids' => [
			'multiple' => true,
			'numeric' => ['msg' => 'num'],
			'exists' => ['args' => ['tags', 'id'], 'msg' => 'exists'],
		],
        'body' => [
            'required' => ['msg' => 'Post body is required.'],
            'min' => ['args' => [20], 'msg' => 'Post must be equal or longer than 20 characters.'],
        ],
		'user_id' => [
			'required' => ['msg' => ''],
			'numeric' => ['msg' => ''],
			'exists' => ['args' => ['users', 'id'], 'msg' => ''],
		]
    ];

    public function __construct()
    {
        parent::__construct('posts');
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
		
        $this->validation->check([
            'title' => $this->title,
            'label' => $this->label,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'tag_ids' => $this->tag_ids,
			'body' => $this->body,
			'user_id' => UsersModel::currentLoggedInUserId(),
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
	
	private $dep = [
		'binding' => [
			'category' => 'categories',
			'tags' => 'tags'
		],
		'select' => [
			'categories' => [
				'select' => ['*'],
			],
			'tags' => [
				'select' => ['*'],
			]
		],
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
			'posts_categories' => [
				'insert' => [
					'post_id' => 'id',
					'category_id' => 'category_id'
				],
			],
			'posts_tags' => [
				'insert' => [
					'post_id' => 'id',
					'tag_id' => 'tag_ids'
				]
			],
		],
		'update' => [
			'posts_categories' => [
				'delete' => [
					'post_id' => 'id'
				],
				'insert' => [
					'post_id' => 'id',
					'category_id' => 'category_id'
				],
			],
			'posts_tags' => [
				'delete' => [
					'post_id' => 'id'
				],
				'insert' => [
					'post_id' => 'id',
					'tag_id' => 'tag_ids'
				]
			],
		],
		'delete' => [
			'posts_categories' => [
				'delete' => [
					'post_id' => 'id'
				]
			],
			'posts_tags' => [
				'delete' => [
 					'post_id' => 'id'
				]
			]
		]
	];
	
	public function getAdditionalInfo()
	{
		if (empty($this->id))
		{
			return false;
		}
		
		foreach ($this->dependencies['binding'] as $property => $object)
		{
			if (empty($this->{$property}))
			{
				$this->{$property} = ModelMediator::make($this->_table . $object, 
														 $property . 'ForPost', 
														 [$this->id, 
														  ['values' => $this->dependencies['select'][$object]]]);
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
	
	private function getCategory()
	{
		if (empty($this->id))
		{
			return false;
		}

		if (empty($this->category))
		{
			$this->category = ModelMediator::make('postsCategories', 'categoryForPost', [$this->id]);
		}
		
		return $this->category;
	}
	
	private function getTags()
	{
		if (empty($this->id))
		{
			return false;
		}

		if (empty($this->tags))
		{
			$this->tags = ModelMediator::make('postsTags', 'tagsForPost', [$this->id]);
		}
		
		return $this->tags;
	}

    public function findBySlug($slug, $values = '*')
    {
        $validation = new Validator;
		
        if (!$validation->check(['slug' => $slug], $this->validationRules['slug'], false))
        {
			return false;
        }
		
		$dataToFind = [
			'values' => $values,
			'conditions' => ['slug' => $slug]
		];
		
		if (!$post = $this->findFirst($dataToFind))
		{
			return false;
		}
		
		$post->getAdditionalInfo();
		
		return $post;
    }
	
	public function lastFromByCategoryId($limit, $offset, $categoryId, $class = false)
	{
		$offset += 1;
		$class = ($class) ? get_class($this) : false;
		$sql = 'SELECT * FROM posts WHERE ' .
				'id IN (SELECT post_id FROM posts_categories WHERE category_id = ?) ' .
				'AND id < ? ORDER BY id DESC LIMIT ?';
		return $this->_db->query($sql, [$categoryId, $offset, $limit], $class)->results();
	}
	
	public function lastFromWhere($amount = 1, $from = 0, $where = [], $params = [], $class = true, $additionalInfo = true)
	{
		if (!empty($where))
		{
			if (!empty($where['tag']))
			{
				$params['conditions'] = Helper::repeatString();
			}
		}
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
	
	public function lastFromByCategorySlug($limit, $offset, $slug, $params = [])
	{
		$category_id = ModelMediator::make('categories', 'idBySlug', [$slug]);
		$post_ids = ModelMediator::make('postsCategories', 'postIdsByCategoryId', [$category_id]);
		$params['conditions'] = Helper::repeatString(' id = ?', count($post_ids), ' OR');
		
		return $this->lastFrom($limit, $offset, $params);
	}
	
	public function save()
	{
	    if ($this->id)
		{
			$postToEdit = $this->findById($this->id);

			$data = [
				'title' => $this->title,
				'label' => $this->label,
				'slug' => $this->slug,
				'body' => $this->body,
				'updated_at' => date('Y-m-d H:i:s'),
			];

			if (!$this->update($this->id, $data, true))
			{
				return false;
			}

			return true;
		}
		else
		{
			$data = [
				'title' => $this->title,
				'label' => $this->label,
				'slug' => $this->slug,
				'body' => $this->body,
				'user_id' => UsersModel::currentLoggedInUserId(),
			];

			if (!$this->insert($data, true))
			{
				return false;
			}
			
			return false;
			
        	return $this->insertPost();
		}
	}

	private function insertPost()
    {
        $data = [
            'title' => $this->title,
            'label' => $this->label,
            'slug' => $this->slug,
            'body' => $this->body,
            'user_id' => UsersModel::currentLoggedInUserId(),
        ];

        if (!$this->insert($data))
        {
           	return false;
        }

        $lastPostId = $this->lastInsertId();

		$data = [
            'post_id' => $lastPostId,
            'category_id' => $this->category_id
        ];

        if (!ModelMediator::make('postsCategories', 'insert', [$data]))
        {
            return false;
        }

        $data = [
            'post_id' => $lastPostId,
            'tag_id' => $this->tag_ids
        ];
		
        return ModelMediator::make('postsTags', 'insertMultiple', [$data]);
    }

    public function formValues()
    {
        return Helper::linkAssociative($this->formValues, $_POST);
    }

    public function truncateText($length = 300)
    {
        $bodyLength = strlen($this->body);

        if ($bodyLength > $length)
        {
            $this->body = rtrim(substr($this->body, 0, $length), ". ") . '...';
        }
    }

    public function prepareForDisplay()
    {
		$this->prepareTagIds();
		$this->prepareTagsString();
    }

    public function getIds($params = [])
    {
		$params['values'] = 'id';
		$params['order'] = 'id DESC';
		
        $result = $this->all($params);

        return ArrayHelper::flattenSingles($result);
    }
	
	public function prepareTagIds()
	{
		if (!isset($this->tag_ids))
		{
			$this->tag_ids = ArrayHelper::flattenSingles(
				ModelMediator::make('postsTags', 'find', 
									[['values' => 'tag_id', 
									  'conditions' => 'post_id = ?', 
									  'bind' => [$this->id]], false]));
		}
		
		return $this->tag_ids;
	}
	
	public function prepareTagsString()
	{
		$tmp = '';
		
		if (!empty($this->tags) && $this->tags[0] instanceof TagsModel)
		{
			foreach ($this->tags as $tag)
			{
				$tmp .= $tag->prepareForDisplay() . ' ';
			}
		}
		
		$this->tagsString = rtrim($tmp, ' ');
	}
}