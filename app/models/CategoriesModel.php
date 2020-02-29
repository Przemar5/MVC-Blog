<?php


class CategoriesModel extends Model
{
    public $id, $name, $slug, $deleted, $numOfPosts;

	private $validationRules = [
        'slug' => [
            'required' => ['msg' => 'Slug is required.'],
            'min' => ['args' => [6], 'msg' => 'Slug must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Slug cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+'], 'msg' => 'Slug contains illegal characters.'],
        ],
	];
	
    public function __construct()
    {
        parent::__construct('categories');
		
//		$this->loadModel('postsCategories');
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
		
//		$category->getAdditionalInfo();
		
		return $category;
    }
	
	public function getAdditionalInfo()
	{
		if (!isset($this->numOfPosts))
		{
			//$this->numOfPosts = $this->postsCategoriesModel->count($this->id);
		}
	}
}
