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
		'delete' => [
			'posts_tags' => [
				'delete' => [
					'tag_id' => 'id'
				],
			]
		]
	];
	
	public function deleteWithDependencies()
	{
		if (!$this->check(true))
		{
			return false;
		}
		
		if (!$this->delete($this->id))
		{
			return false;
		}
		
		return $this->actOnDependencies('delete');
	}
	
	public function actOnDependencies($mode)
	{	
		foreach ($this->dependencies[$mode] as $table => $methods)
		{
			foreach ($methods as $method => $values)
			{
				$model = Helper::tableToModelName($table);
				$where = [];
				
				foreach ($values as $key => $value)
				{
					$where[$key] = $this->{$value};
				}
				
				if (!ModelMediator::make($model, $method . 'By', [$where]))
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
//	public function deleteWithDependencies()
//	{
//		if (!$this->check(true))
//		{
//			return false;
//		}
//		
//		if (!$this->delete($this->id))
//		{
//			return false;
//		}
//		
//		foreach ($this->dependencies as $method => $values)
//		{
//			foreach ($values as $table => $value)
//			{
//				$model = Helper::tableToModelName($table);
//				$where = [$value[0], $this->{$value[1]}];
//				
//				if (!ModelMediator::make($model, 'deleteBy', $where))
//				{
//					return false;
//				}
//			}
//		}
//		
//		return true;
//	}
	
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
            'name' => $this->name,
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
		$this->removeUniqueException('name');
	}
	
	private function validationRulesForUpdate()
	{
		$this->addUniqueException('name');
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
			if (!$postToEdit = $this->findById($this->id))
			{
				return false;
			}
		
			$data = [
				'name' => $this->name,
			];

			return $this->update($this->id, $data);
		}
		else
		{
        	$data = [
				'name' => $this->name,
			];
			
			return $this->insert($data);
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
