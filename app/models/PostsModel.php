<?php


class PostsModel extends Model
{
    public $id, $label, $title, $slug, $category_id, $tags, $body, $user_id, $created_at, $updated_at, $deleted;

    private $slugValidation = [
        'slug' => [
            'required' => [],
            'min' => ['args' => [6]],
            'max' => ['args' => [150]],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+']]
        ]
    ];

    private $validationRules = [
        'title' => [
            'required' => ['msg' => 'Title is required.'],
            'min' => ['args' => [6], 'msg' => 'Title must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Title cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Title contains illegal characters.'],
        ],
        'label' => [
            'required' => ['msg' => 'Label is required.'],
            'min' => ['args' => [6], 'msg' => 'Label must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Label cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z \@\+\/\?\!\$\_\-]+'], 'msg' => 'Label contains illegal characters.'],
        ],
        'slug' => [
            'required' => ['msg' => 'Slug is required.'],
            'min' => ['args' => [6], 'msg' => 'Slug must be equal or longer than 6 characters.'],
            'max' => ['args' => [150], 'msg' => 'Slug cannot be longer than 150 characters.'],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+'], 'msg' => 'Slug contains illegal characters.'],
        ],
        'category_id' => [
            'required' => ['msg' => 'ategory is required.'],
            'numeric' => ['msg' => 'Invalid category.'],
            'regex' => ['args' => ['[0-9]{1,7}'], 'msg' => 'Invalid category.'],
            'exists' => ['msg' => "Category doesn't exist."]
        ],
        'body' => [
            'required' => ['msg' => 'Post body is required.'],
            'min' => ['args' => [20], 'msg' => 'Post must be equal or longer than 20 characters.'],
        ]
    ];

    public function __construct()
    {
        parent::__construct('posts');
    }

    public function check()
    {
        $this->validation = new Validator;

        $this->validation->check([
            'title' => $this->title,
            'label' => $this->label,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'tags' => $this->tags,
            'body' => $this->body,
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

        if ($validation->check(['slug' => $slug], $this->slugValidation, false))
        {
            return $this->findFirst(['slug' => $slug]);
        }
        return false;
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