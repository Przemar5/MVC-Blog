<?php


class PostsModel extends Model
{
    public $id, $label, $title, $slug, $body, $user_id, $created_at, $updated_at, $deleted;

    private $slugValidation = [
        'slug' => [
            'required' => [],
            'max' => ['args' => [150]],
            'regex' => ['args' => ['[0-9a-zA-Z_\-]+']]
        ]
    ];

    public function __construct()
    {
        parent::__construct('posts');
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