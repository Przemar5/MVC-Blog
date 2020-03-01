<?php


class CategoriesModel extends Model
{
    public $id, $name, $slug, $deleted, $numOfPosts;

	private $validationRules = [
        'name' => [
            'required' => ['msg' => 'Category name is required.'],
            'min' => ['args' => [6], 'msg' => 'Category name must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Category name cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z _\-]+'], 'msg' => 'Category name contains illegal characters.'],
        ],
        'slug' => [
            'required' => ['msg' => 'Slug is required.'],
            'min' => ['args' => [6], 'msg' => 'Slug must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Slug cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+'], 'msg' => 'Slug contains illegal characters.'],
        ],
	];
	private $dependencies = [
		'posts_categories' => [
			'key' => ['id', 'category_id'],
			'delete' => ['delete'],
		]
	];
	
    public function __construct()
    {
        parent::__construct('categories');
    }

	public function check()
    {
        $this->validation = new Validator;
		
        $this->validation->check([
            'name' => $this->name,
            'slug' => $this->slug,
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
	
	public function idBySlug($slug)
	{
		$this->validation = new Validator;
		
        $this->validation->check([
            'slug' => $this->slug,
        ], $this->validationRules['slug']);
		
		if ($this->validation->passed())
        {
			$data = [
				'values' => ['id'],
				'conditions' => ['slug' => $slug]
			];
			
            return ArrayHelper::flattenSingles($this->findFirst($data));
        }
        else
        {
            $this->errors = $this->validation->errors();

            return false;
        }
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
		
		if (!$category = $this->findFirst($dataToFind))
		{
			return false;
		}
		
		return $category;
    }
	
	public function getAdditionalInfo()
	{
		if (!isset($this->numOfPosts))
		{
			$this->numOfPosts = ModelMediator::make('postsCategories', 'count', [['conditions' => 'category_id = ?', 'bind' => [$this->id]]]);
		}
	}
	
	public function save()
	{
		$data = [
			'name' => $this->name,
			'slug' => $this->slug,
		];
		
	    if ($this->id)
		{
			return $this->update($this->id, $data);
		}
		else
		{
        	return $this->insert($data);
		}
	}

	public function popErrors()
    {
        $errors = $this->errors;
        unset($this->errors);

        return $errors;
    }
}
