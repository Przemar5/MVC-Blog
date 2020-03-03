<?php


class TagsModel extends Model
{
    public $id, $name, $deleted, $numOfPosts;

	private $validationRules = [
        'name' => [
            'required' => ['msg' => 'Category name is required.'],
            'min' => ['args' => [1], 'msg' => 'Category name must be equal or longer than 1 character.'],
            'max' => ['args' => [150], 'msg' => 'Category name cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z _\-]+'], 'msg' => 'Category name contains illegal characters.'],
        	'unique' => ['args' => ['tags', 'name'], 'msg' => 'Category name must be unique'],
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
        parent::__construct('tags');
    }
	
	public function getAdditionalInfo()
	{
		if (!isset($this->numOfPosts))
		{
			$this->numOfPosts = ModelMediator::make('postsTags', 'count', [['conditions' => 'tag_id = ?', 'bind' => [$this->id]]]);
		}
	}
	
    public function findByName($name, $values = '*')
    {
        $validation = new Validator;
		
        if (!$validation->check(['name' => $name], $this->validationRules['name'], false))
        {
			return false;
        }
		
		$dataToFind = [
			'values' => $values,
			'conditions' => ['name' => $name]
		];
		
		if (!$tag = $this->findFirst($dataToFind))
		{
			return false;
		}
		
		return $tag;
    }
	
	public function prepareForDisplay()
	{
		return '<span class="badge badge-secondary">' . $this->name . '</span>';
	}
}
