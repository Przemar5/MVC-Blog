<?php


class PostsModel extends Model
{
    public $id, $label, $title, $slug, $category_id, $category = null, $tag_ids, $tags = [],
			$tagsString = '', $body, $user_id, $created_at, $updated_at, $deleted;

    private $validationRules = [
        'title' => [
            'required' => ['msg' => 'Post title is required.'],
            'min' => ['args' => [6], 'msg' => 'Post title must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Post title cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Post title contains illegal characters.'],
        ],
        'label' => [
            'required' => ['msg' => 'Post label is required.'],
            'min' => ['args' => [6], 'msg' => 'Post label must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Post label cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Post label contains illegal characters.'],
        ],
        'slug' => [
            'required' => ['msg' => 'Slug is required.'],
            'min' => ['args' => [6], 'msg' => 'Slug must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Slug cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+'], 'msg' => 'Slug contains illegal characters.'],
        ],
		'category_id' => [
            'required' => ['msg' => 'Category is required.'],
            'numeric' => ['msg' => 'Invalid category.'],
            'regex' => ['args' => ['[0-9]{1,7}'], 'msg' => 'Invalid category.'],
            'exists' => ['args' => ['categories', 'id'], 'msg' => "Category doesn't exist."]
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
    private $formValues = ['title', 'label', 'slug', 'category_id', 'tags', 'body', 'user_id'];
	private $dependencies = [
		'posts_tags' => [
			'key' => ['id', 'post_id'],
			'select' => ['select'],
			'update' => ['delete', 'insert'],
			'delete' => ['delete'],
		], 
		'posts_categories' => [
			'key' => ['id', 'post_id'],
			'select' => ['select'],
			'update' => ['delete', 'insert'],
			'delete' => ['delete'],
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
			$this->validationRules['user_id']['match'] = ['args' => [$this->user_id], 'msg' =>''];
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
            $this->errors = $this->validation->errors();

            return false;
        }
    }
	
	public function getAdditionalInfo()
	{
		$this->getCategory();
		$this->getTags();
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
	
	public function lastFromByCategoryId($limit, $offset, $categoryId, $params = [])
	{
		$post_ids = ModelMediator::make('postsCategories', 'postIdsForCategoryId', [$categoryId, 'post_id DESC']);
		$params['conditions'] = Helper::repeatString(' id = ?', count($post_ids), ' OR');
		$params['bind'] = $post_ids;
		
		return $this->lastFrom($limit, $offset, $params);
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
			$this->updatePost();
		}
		else
		{
        	$this->insertPost();
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
	
	private function updatePost()
	{
		$postToEdit = $this->findById($this->id);
		
		$data = [
			'title' => $this->title,
			'label' => $this->label,
			'slug' => $this->slug,
			'body' => $this->body,
			'updated_at' => date('Y-m-d H:i:s'),
		];
		
		if (!$this->update($this->id, $data))
		{
			return false;
		}
		
		if (!ModelMediator::make('postsCategories', 'updateCategoryForPost', [$this->id, $this->category_id]))
		{
			return false;
		}
		
		if (!ModelMediator::make('postsTags', 'deleteForPost', [$this->id, $this->category_id]))
		{
			return false;
		}
		
		if (!ModelMediator::make('postsTags', 'insertMultiple', [['post_id' => $this->id, 'tag_id' => $this->tag_ids]]))
		{
			return false;
		}
		
		return true;
	}

	public function popErrors()
    {
        $errors = $this->errors;
        unset($this->errors);

        return $errors;
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