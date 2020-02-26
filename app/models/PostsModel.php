<?php


class PostsModel extends Model
{
    public $id, $label, $title, $slug, $category_id, $tags, $body, $user_id, $created_at, $updated_at, $deleted;

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


    public function __construct()
    {
        parent::__construct('posts');
    }
	
	private function loadModelFile($table)
	{
		$path = ROOT . DS . 'app' . DS . 'models' . DS . $table . 'Model.php';
		
		if (file_exists($path))
		{
			return require_once($path);
		}
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
            'tags' => $this->tags,
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

    public function findBySlug($slug)
    {
        $validation = new Validator;

        if ($validation->check(['slug' => $slug], $this->validationRules['slug'], false))
        {
            return $this->findFirst(['conditions' => ['slug' => $slug]]);
        }
        return false;
    }
	
	public function save()
	{
	    if ($this->id)
		{
			return $this->update($this->id, [
				'title' => $this->title,
				'label' => $this->label,
				'slug' => $this->slug,
				'category_id' => $this->category_id,
				'body' => $this->body,
				'updated_at' => Helper::currentTimestamp(),
			]);
		}
		else
		{
			return $this->insert([
				'title' => $this->title,
				'label' => $this->label,
				'slug' => $this->slug,
				'category_id' => $this->category_id,
				'body' => $this->body,
				'user_id' => UsersModel::currentLoggedInUserId(),
			]);
		}
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
        $this->body = substr($this->body, 0, $length);
    }

    public function divideParagraphs()
    {
//        preg_replace('/(\r*\n)+/g', '</p><p>', $this->body);
//        $this->
    }
}